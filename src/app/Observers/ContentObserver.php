<?php

namespace App\Observers;

use App\Models\Content;

class ContentObserver
{
    /**
     * Handle the Content "created" event.
     *
     * @param  \App\Models\Content  $content
     * @return void
     */
    public function created(Content $content)
    {
        //
    }

    /**
     * Handle the Content "updating" event.
     *
     * @param  \App\Models\Content  $content
     * @return void
     */
    public function updating(Content $content)
    {
        if ($content->isDirty('context')) {
            $content->delivered_at = date('Y-m-d');
        }
    }

    /**
     * Handle the Content "deleted" event.
     *
     * @param  \App\Models\Content  $content
     * @return void
     */
    public function deleted(Content $content)
    {
        //
    }

    /**
     * Handle the Content "restored" event.
     *
     * @param  \App\Models\Content  $content
     * @return void
     */
    public function restored(Content $content)
    {
        //
    }

    /**
     * Handle the Content "force deleted" event.
     *
     * @param  \App\Models\Content  $content
     * @return void
     */
    public function forceDeleted(Content $content)
    {
        //
    }
}
