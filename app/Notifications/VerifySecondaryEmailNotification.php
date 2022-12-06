<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VerifySecondaryEmailNotification extends Notification
{
	use Queueable;

	public $secondaryEmail;

	/**
	 * Create a new notification instance.
	 *
	 * @return void
	 */
	public function __construct($secondaryEmail)
	{
		$this->secondaryEmail = $secondaryEmail;
	}

	/**
	 * Get the notification's delivery channels.
	 *
	 * @param mixed $notifiable
	 *
	 * @return array
	 */
	public function via($notifiable)
	{
		return ['mail'];
	}

	/**
	 * Get the mail representation of the notification.
	 *
	 * @param mixed $notifiable
	 *
	 * @return \Illuminate\Notifications\Messages\MailMessage
	 */
	public function toMail($notifiable)
	{
		$url = env('APP_FRONTEND_BASE_URL') . 'profile/new-email/verify?id=' . base64_encode($this->secondaryEmail->id) . '&email=' . base64_encode($this->secondaryEmail->secondary_email);

		return (new MailMessage)
					->subject('Verify Secondary Email')
					->greeting('Hello!')
					->line('If this email is yours, please verify:')
					->line($this->secondaryEmail->secondary_email)
					->action('Verify secondary email', $url);
	}

	/**
	 * Get the array representation of the notification.
	 *
	 * @param mixed $notifiable
	 *
	 * @return array
	 */
	public function toArray($notifiable)
	{
		return [
		];
	}
}
