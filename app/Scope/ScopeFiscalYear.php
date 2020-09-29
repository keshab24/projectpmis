<?php
///**
// * Created by PhpStorm.
// * User: USER
// * Date: 10/2/2018
// * Time: 3:03 PM
// */
//
//namespace PMIS\Scope;
//
//use Illuminate\Database\Eloquent\Builder;
//use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\ScopeInterface;
//
//
//class ScopeFiscalYear implements ScopeInterface
//{
//    public function apply(Builder $builder, Model $model)
//    {
//        $builder->where('id', '>=',16);
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
//
//}