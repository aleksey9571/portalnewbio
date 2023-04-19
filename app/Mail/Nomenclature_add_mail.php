<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Nomenclature_add_mail extends Mailable{
    use Queueable, SerializesModels;

    protected $name, $generator, $artikul, $message;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name, $generator, $artikul, $message){
        $this->name = $name;
        $this->generator = $generator;
        $this->artikul = $artikul;
        $this->message = $message;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(){
        return $this
            ->subject('Добавление номенклатуры')
            ->markdown('emails.nomenclature_add_mail')
            ->with([
                'name' => $this->name,
                'generator' => $this->generator,
                'artikul' => $this->artikul,
                'message' => $this->message,
            ]);
    }
}
