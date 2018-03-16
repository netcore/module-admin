<?php

namespace Modules\Admin\Tests;

use Module;
use Modules\Setting\Models\Setting;
use Swift_Mailer;
use Swift_SmtpTransport;
use Tests\TestCase;

class MailConfigTest extends TestCase
{
    /**
     * Test mail credentials and connection.
     *
     * @return void
     */
    public function testMailConnection(): void
    {
        if (!config('netcore.module-admin.sends_emails', false)) {
            $this->assertTrue(true);
            return;
        }

        $config = $this->getMailConfig();

        $host = $config['host'] ?? null;
        $port = $config['port'] ?? null;
        $user = $config['username'] ?? null;
        $pass = $config['password'] ?? null;

        // Check connection.
        $transport = new Swift_SmtpTransport($host, $port);
        $transport->setUsername($user);
        $transport->setPassword($pass);

        $mailer = new Swift_Mailer($transport);
        $res = $mailer->getTransport()->start();

        $this->assertNull($res);
    }

    /**
     * Get mail config.
     *
     * @return array
     */
    private function getMailConfig(): array
    {
        if (!Module::find('Setting')) {
            return config('mail');
        }

        $settings = Setting::whereGroup('mail')->get();

        $config = $settings->mapWithKeys(function (Setting $setting) {
            $translation = $setting->translations->first();

            $key = str_replace('mail.mail_', '', $setting->key);
            $key = str_replace('user', 'username', $key);

            return [
                $key => $translation->value ?? '',
            ];
        })->toArray();

        return array_merge(
            config('mail'), $config
        );
    }
}