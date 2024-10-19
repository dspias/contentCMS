<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Http\Requests\NovaRequest;

class Content extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Content::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The relationships that should be eager loaded on index queries.
     *
     * @var array
     */
    public static $with = ['writer', 'workProvider'];

    /**
     * Default ordering for index query.
     *
     * @var array
     */
    public static $sort = [
        'delivery_date' => 'asc',
        'id' => 'desc',
    ];
    
    public static function indexQuery(NovaRequest $request, $query)
    {
        $query->whereNull('delivered_at');
        if (empty($request->get('orderBy'))) {
            $query->getQuery()->orders = [];
    
            return $query->orderBy(key(static::$sort), reset(static::$sort));
        }
    
        return $query;
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'student',
        'writer',
        'delivery_date',
        'created_at',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make(__('ID'), 'id')->sortable()->hideFromDetail(),

            BelongsTo::make('Student', 'student', 'App\Nova\Student')
                ->searchable()
                ->required(),

            BelongsTo::make('WorkProvider', 'workProvider', 'App\Nova\WorkProvider')
                ->searchable()
                ->required(),
                
            BelongsTo::make('Writer', 'writer', 'App\Nova\Writer')
                ->searchable()
                ->nullable(),
            
            Text::make(__('Unit Name'), 'unit_name')
                ->hideFromIndex()
                ->rules(['required_without:unit_code,', 'string', 'max:100']),
            
            Text::make(__('Unit Code'), 'unit_code')
                ->hideFromIndex()
                ->rules(['required_without:unit_name,', 'string', 'max:100']),

            Number::make(__('Word Count'), 'word_count')
                ->hideFromIndex()
                ->step(1)
                ->rules(['required', 'integer']),

            Date::make(__('Date of Entry'), 'created_at')
                ->sortable()
                ->onlyOnDetail(),

            Date::make(__('Date of Receive'), 'received_at')
                ->hideFromIndex()
                ->sortable()
                ->rules(['nullable']),

            Date::make(__('Date of Submission'), 'delivery_date')
                ->sortable()
                ->required(),
            
            Date::make(__('Summitted at'), 'delivered_at')
                ->onlyOnDetail(),
            
            Trix::make(__('Result (Count as submitted)'), 'context')
                ->hideFromIndex()
                ->rules(['nullable']),
            
            Trix::make(__('Comment'), 'comment')
                ->hideFromIndex()
                ->rules(['nullable']),
            
            Number::make(__('Price (£)'), 'price')
                ->hideFromIndex()
                ->step(0.01)
                ->rules(['required', 'numeric']),
            
            Number::make(__('Amount Paid (£)'), 'paid')
                ->hideFromIndex()
                ->step(0.01)
                ->rules(['nullable', 'numeric']),
            
            Text::make(__('Payable (£)'), function() {
                    return $this->price - ($this->paid ?? 0);
                })->exceptOnForms(),

            Text::make(__('Writer commission (BDT)'), function() {
                    $words = ceil($this->word_count / 250);
                    return $words * $this->writer->commission;
                })->exceptOnForms(),

            Boolean::make(__('Paid to writer'), 'paid_to_writer')
                ->hideFromIndex()
                ->rules(['nullable']),

            Text::make(__('Work Provider commission (£)'), function() {
                return ($this->price * $this->workProvider->commission)/100;
            })->exceptOnForms(),

            Boolean::make(__('Paid to provider'), 'paid_to_provider')
                ->hideFromIndex()
                ->rules(['nullable']),
            
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
}
