<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Notifications\Messages\MailMessage;

class AuthServiceProvider extends ServiceProvider
{
	/**
	 * The model to policy mappings for the application.
	 *
	 * @var array<class-string, class-string>
	 */
	protected $policies = [
		// 'App\Models\Model' => 'App\Policies\ModelPolicy',
	];

	/**
	 * Register any authentication / authorization services.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->registerPolicies();

		VerifyEmail::toMailUsing(function ($notifiable, $url) {
			$url = env('APP_FRONTEND_BASE_URL') . 'verification-verified/?id=' . base64_encode($notifiable->id) . '&email=' . base64_encode($notifiable->email);

			return (new MailMessage)
				->action('Verify Email Address', $url);
		});

		ResetPassword::createUrlUsing(function ($user, string $token) {
			return env('APP_FRONTEND_BASE_URL') . 'reset-password?token=' . $token . '&email=' . base64_encode($user->email);
		});
	}
}
