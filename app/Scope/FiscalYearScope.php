<?php
//namespace PMIS\Scope;
//use Illuminate\Database\Eloquent\Builder;
//use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\ScopeInterface;
//
///**
// * Created by PhpStorm.
// * User: USER
// * Date: 10/2/2018
// * Time: 2:45 PM
// */
//class FiscalYearScope implements ScopeInterface
//{
//
//
//    /**
//     * Apply the scope to a given Eloquent query builder.
//     *
//     * @param  \Illuminate\Database\Eloquent\Builder $builder
//     * @param  \Illuminate\Database\Eloquent\Model $model
//     * @return void
//     */
//    public function apply(Builder $builder, Model $model)
//    {
//        $builder->where('published', '=', true);
//    }
//
//    /**
//     * Remove the scope from the given Eloquent query builder.
//     *
//     * @param  \Illuminate\Database\Eloquent\Builder $builder
//     * @param  \Illuminate\Database\Eloquent\Model $model
//     *
//     * @return void
//     */
//    public function remove(Builder $builder, Model $model)
//    {
//        // TODO: Implement remove() method.
//    }
//}