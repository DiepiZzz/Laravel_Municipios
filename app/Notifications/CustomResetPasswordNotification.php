<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;

class CustomResetPasswordNotification extends ResetPasswordNotification
{
    use Queueable;

    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Obtener las representaciones de notificación del correo.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        
        $resetUrl = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ]));

        $fromAddress = config('mail.from.address');
        $fromName = config('mail.from.name');

        
        return (new MailMessage)
                    ->from($fromAddress, $fromName)
                    ->replyTo($fromAddress, $fromName)
                    ->subject('Restablecimiento de Contraseña')
                    ->line('Hola,')
                    ->line('Hemos recibido una solicitud para restablecer la contraseña de tu cuenta.')
                    ->line('Haz clic en el siguiente enlace para restablecer tu contraseña:')
                    ->line($resetUrl) 
                    ->line('Este enlace caducará en ' . config('auth.passwords.users.expire') . ' minutos.')
                    ->line('Si no solicitaste esto, ignora este correo.')
                    ->salutation('Saludos,')
                    ->line(config('app.name'));
                    
    }

    /**
     * Obtener las representaciones de notificación del array.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            
        ];
    }
}
