<?php

class TestJob implements \Illuminate\Contracts\Queue\ShouldQueue
{
    use \Illuminate\Bus\Queueable;

    private $_payload;
    public function __construct($_payload) {
        $this->_payload = $_payload;
    }
}

describe('SingleDispatchSpec', function() {
    using(['database transactions'], function () {
        it('does not allow to add same job twice', function() {

            $pay = uniqid(); $pay2 = uniqid();
            $id  = dispatch((new TestJob($pay))->onQueue("queue"));
            $id2 = dispatch((new TestJob($pay))->onQueue("queue"));
            $id3 = dispatch((new TestJob($pay2))->onQueue("queue"));
            $id4 = dispatch((new TestJob($pay2))->onQueue("testing"));

            expect($id2)->toBe(false);
            expect($id)->not->toBe($id3);
            expect($id4)->not->toBe(false);
            expect($id3)->not->toBe($id4);

        });
    });

});