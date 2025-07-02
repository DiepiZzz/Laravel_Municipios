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
        ], false)); // false para URL relativa si APP_URL es localhost, o true para absoluta

        // Configuración de remitente y respuesta explícita
        $fromAddress = config('mail.from.address');
        $fromName = config('mail.from.name');

        return (new MailMessage)
                    ->from($fromAddress, $fromName) // Usa la configuración de .env
                    ->replyTo($fromAddress, $fromName) // Añade una dirección de respuesta
                    ->subject('Restablecimiento de Contraseña') // Asunto muy simple
                    ->line('Hola,') // Saludo simple
                    ->line('Hemos recibido una solicitud para restablecer la contraseña de tu cuenta.')
                    ->action('Restablecer Contraseña', $resetUrl) // Botón claro
                    ->line('Si no solicitaste esto, ignora este correo.') // Mensaje de seguridad muy simple
                    ->salutation('Saludos,')
                    ->line(config('app.name')); // Nombre de tu aplicación al final
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
            //
        ];
    }
}
