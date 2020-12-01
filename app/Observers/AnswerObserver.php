<?php

namespace App\Observers;

use App\Models\Answer;

class AnswerObserver
{
    /**
     * Handle the Answer "created" event.
     *
     * @param Answer $answer
     * @return void
     */
    public function created(Answer $answer)
    {
        $answer->question->incrementPopularity();
    }

    /**
     * Handle the Answer "updated" event.
     *
     * @param Answer $answer
     * @return void
     */
    public function updated(Answer $answer)
    {
        //
    }

    /**
     * Handle the Answer "deleted" event.
     *
     * @param Answer $answer
     * @return void
     */
    public function deleted(Answer $answer)
    {
        //
    }

    /**
     * Handle the Answer "restored" event.
     *
     * @param Answer $answer
     * @return void
     */
    public function restored(Answer $answer)
    {
        //
    }

    /**
     * Handle the Answer "force deleted" event.
     *
     * @param Answer $answer
     * @return void
     */
    public function forceDeleted(Answer $answer)
    {
        //
    }
}
