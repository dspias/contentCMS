<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Http\Requests\NovaRequest;

class WorkProvider extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\WorkProvider::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'name',
        'email'
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

            Text::make(__('Name'), 'name')
                ->sortable()
                ->rules(['required', 'string', 'max:100']),
                
            Text::make(__('Email'), 'email')
                ->rules(['required', 'string', 'max:150']),
            
            Text::make(__('Address'), 'address')
                ->hideFromIndex()
                ->rules(['required', 'string', 'max:150']),
            
            Number::make(__('Commission (%)'), 'commission')
                ->step(0.01)
                ->rules(['required', 'numeric', 'max:100']),
            
            Number::make(__('Total commission (£)'), function() {
                    $total = $this->contents()->sum('price');
                    return ($total * $this->commission)/100;
                })
                ->showOnDetail(),

            Trix::make(__('Details'), 'details')
                ->hideFromIndex()
                ->rules(['nullable']),

            HasMany::make('Contents'),
            
            HasMany::make('Archive'),
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
