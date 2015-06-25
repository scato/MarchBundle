<?php

namespace LearningTests;

use PHPUnit_Framework_TestCase as TestCase;
use Stomp;

class StompTest extends TestCase
{
    /**
     * @test
     */
    public function shouldSendAndReceiveMessages()
    {
        $queue  = '/queue/foo';
        $msg    = 'bar';

        /* connection */
        $stomp = new Stomp('tcp://localhost:61613', 'guest', 'guest');

        /* send a message to the queue 'foo' */
        $stomp->send($queue, $msg);

        /* subscribe to messages from the queue 'foo' */
        $stomp->subscribe($queue);

        /* read a frame */
        $frame = $stomp->readFrame();

        /* the only frame we are expecting, is the one that we sent */
        $this->assertEquals($msg, $frame->body);

        /* acknowledge that the frame was received */
        $stomp->ack($frame);

        /* remove the subscription */
        $stomp->unsubscribe($queue);

        /* close connection */
        unset($stomp);
    }
}
