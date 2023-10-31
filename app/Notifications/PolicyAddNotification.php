<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PolicyAddNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public $data;
    public function __construct($data)
    {
        $this->data=$data;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }
    public function toArray($notifiable)
    {
        return [
            'policy_type'=>$this->data['policy_type'],
            'policy_id'=>$this->data['id'],
            'icon'=>$this->data['notification_type'],
            'message'=>$this->data['message'],
            'policy_number'=>!empty($this->data['policy_number'])?$this->data['policy_number']:'',
        ];
    }
}
