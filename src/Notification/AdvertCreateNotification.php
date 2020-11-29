<?php
namespace App\Notification;
use App\Entity\AdminUser;
use App\Entity\Advert;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Notifier\Message\EmailMessage;
use Symfony\Component\Notifier\Notification\EmailNotificationInterface;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\Recipient\Recipient;

class AdvertCreateNotification extends Notification implements EmailNotificationInterface
{
    private Advert $advert;
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    public function __construct(Advert $advert, EntityManagerInterface $entityManager)
    {
        parent::__construct("Une annonce vient d'être créée !");
        $this->entityManager = $entityManager;
        $this->advert = $advert;
    }

    public function asEmailMessage(Recipient $recipient, string $transport = null): ?EmailMessage
    {
        $message = EmailMessage::fromNotification($this, $recipient);
        if (null !== $transport) {
            $message->transport($transport);
        }

        $message->getMessage()->from('admin@noreply.com');
        $users = $this->entityManager->getRepository(AdminUser::class)->findAll();
        foreach($users as $user){
            $message->getMessage()->addTo($user->getEmail());
        }

        $message
            ->getMessage()
            ->htmlTemplate('emails/advert_create_notification.html.twig')
            ->context(['advert' => $this->advert])
        ;
        return $message;
    }
    public function getChannels(Recipient $recipient): array
    {
        return ['email'];
    }
}