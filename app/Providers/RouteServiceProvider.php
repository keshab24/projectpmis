<?php

namespace PMIS\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use PMIS\Address;
use PMIS\Allocation;
use PMIS\AuthorizedPerson;
use PMIS\BudgetTopic;
use PMIS\Cheif;
use PMIS\ConstructionType;
use PMIS\Contractor;
use PMIS\District;
use PMIS\Division;
use PMIS\Employee;
use PMIS\Engineer;
use PMIS\ExpenditureTopic;
use PMIS\Expense;
use PMIS\Fiscalyear;
use PMIS\FundStore;
use PMIS\ImplementingMode;
use PMIS\ImplementingOffice;
use PMIS\Income;
use PMIS\IncomeTopic;
use PMIS\JointVenture;
use PMIS\LumpSumBudget;
use PMIS\Month;
use PMIS\Notice;
use PMIS\Payment;
use PMIS\Procurement;
use PMIS\ProcurementDate;
use PMIS\Progress;
use PMIS\ProgressTrack;
use PMIS\Project;
use PMIS\ProjectGroup;
use PMIS\Region;
use PMIS\SalaryHead;
use PMIS\Sector;
use PMIS\State;
use PMIS\User;
use PMIS\Vendor;
use PMIS\Zone;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to the controller routes in your routes file.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'PMIS\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function boot()
    {
        parent::boot();
        /////manage
        /* Region */

        Route::bind('region',function($slug){
            $return= Region::withTrashed()->whereSlug($slug)->first();
            if($return){
                if(Auth::User()->access=="Root Level"){
                    return $return;
                }else{
                    abort(403);
                }
            }else{
                abort(404);
            }
        });

        /* Zone */
        Route::bind('zone',function($slug){
            $return= Zone::withTrashed()->whereSlug($slug)->first();
            if($return){
                if(Auth::User()->access=="Root Level"){
                    return $return;
                }else{
                    abort(403);
                }
            }else{
                abort(404);
            }
        });

        /* State */
        Route::bind('state',function($slug){
            $return= State::withTrashed()->whereSlug($slug)->first();
            if($return){
                if(Auth::User()->access=="Root Level"){
                    return $return;
                }else{
                    abort(403);
                }
            }else{
                abort(404);
            }
        });

        /* District */
        Route::bind('district',function($slug){
            $return= District::withTrashed()->whereSlug($slug)->first();
            if($return){
                if(Auth::User()->access=="Root Level"){
                    return $return;
                }else{
                    abort(403);
                }
            }else{
                abort(404);
            }
        });

        /* Division */
        Route::bind('division',function($slug){
            $return= Division::withTrashed()->whereSlug($slug)->first();
            if($return){
                if(Auth::User()->access=="Root Level"){
                    return $return;
                }else{
                    abort(403);
                }
            }else{
                abort(404);
            }
        });

        /* Divisionchief */
        Route::bind('engineer',function($slug){
            $return= Engineer::withTrashed()->whereSlug($slug)->first();
            return $return;
/*
            if($return){
                if(Auth::User()->access=="Root Level"){
                    return $return;
                }else{
                    abort(403);
                }
            }else{
                abort(404);
            }*/
        });


        /* cheif */
        Route::bind('cheif',function($slug){
            $return= Cheif::withTrashed()->whereSlug($slug)->first();
            if($return){
                if(Auth::User()->access=="Root Level"){
                    return $return;
                }else{
                    abort(403);
                }
            }else{
                abort(404);
            }
        });

        /* Address */
        Route::bind('address',function($id){
            $return =Address::withTrashed()->whereId($id)->first();
            if($return){
                if(Auth::User()->access=="Root Level"){
                    return $return;
                }else{
                    abort(403);
                }
            }else{
                abort(404);
            }

        });

        /* Month */
        Route::bind('month',function($slug){
            $return =Month::withTrashed()->whereSlug($slug)->first();
            if($return){
                if(Auth::User()->access=="Root Level"){
                    return $return;
                }else{
                    abort(403);
                }
            }else{
                abort(404);
            }

        });

        /* Fiscalyear */
        Route::bind('fiscalyear',function($slug){
            $return= Fiscalyear::withTrashed()->whereSlug($slug)->first();
            if($return){
                if(Auth::User()->access=="Root Level"){
                    return $return;
                }else{
                    abort(403);
                }
            }else{
                abort(404);
            }

        });

        /* constructiontype */
        Route::bind('constructiontype',function($slug){
            $return= ConstructionType::withTrashed()->whereSlug($slug)->first();
            if($return){
                if(Auth::User()->access=="Root Level"){
                    return $return;
                }else{
                    abort(403);
                }
            }else{
                abort(404);
            }

        });

        /* ProgressTrack */
        Route::bind('progresstrack',function($slug){
            $return= ProgressTrack::withTrashed()->whereSlug($slug)->first();
            if($return){
                if(Auth::User()->access=="Root Level"){
                    return $return;
                }else{
                    abort(403);
                }
            }else{
                abort(404);
            }
        });

        /* Notice */ // monitoring le aafno antargat ra afno payo, implementing le afno matra, root le sabai !!
        Route::bind('notice',function($slug){
            $return= Notice::withTrashed()->whereSlug($slug)->first();
            return $return;
        });

        /* procurement_date */
        Route::bind('procurement_date',function($id){
            $return= ProcurementDate::find($id);
            if($return){
                return $return;
            }
            abort(404);
        });

        /* user */
        Route::bind('user',function($slug){
            $return = User::withTrashed()->whereSlug($slug)->first();
            return $return;
//            if($return){
//                if(Auth::User()->access=="Root Level" || Auth::User()->access=="Top Level" || Auth::User()==$return){
//                }else{
//                    abort(403);
//                }
//            }else{
//                abort(404);
//            }
        });

        /* Payment */
        Route::bind('payment',function($id){
            $return =Payment::withTrashed()->whereId($id)->first();
            if($return){
                if(Auth::User()->access=="Root Level"){
                    return $return;
                }else{
                    abort(403);
                }
            }else{
                abort(404);
            }
        });

        /* Sector */
        Route::bind('sector',function($slug){
            $return =Sector::withTrashed()->whereSlug($slug)->first();
            if($return){
                if(Auth::User()->access=="Root Level"){
                    return $return;
                }else{
                    abort(403);
                }
            }else{
                abort(404);
            }
        });

        /* ImplementingOffice */
        Route::bind('implementingoffice',function($id){
            $return =ImplementingOffice::withTrashed()->whereId($id)->first();
            return $return;
        });

        Route::bind('authorized_person',function($slug){
            $return =AuthorizedPerson::withTrashed()->whereSlug($slug)->first();
            return $return;
        });

        /* ProjectGroup */
        Route::bind('project-group',function($slug){
            $return =ProjectGroup::withTrashed()->whereSlug($slug)->first();
            return $return;
//            if($return){
//                if(Auth::User()->access=="Root Level"){
//                }else{
//                    abort(403);
//                }
//            }else{
//                abort(404);
//            }
        });

        /* Contractor */
        Route::bind('contractor',function($slug){
            $return = Contractor::withTrashed()->whereSlug($slug)->first();
            return $return;

/*            if($return){
                if(Auth::User()->access=="Root Level"){
                    return $return;
                }else{
                    abort(403);
                }
            }else{
                abort(404);
            }*/
        });
        
        /* Consultant */
        Route::bind('consultant',function($slug){
            $return = Contractor::withTrashed()->whereSlug($slug)->first();
            return $return;
        });
        /* Joint Venture */
        Route::bind('joint_venture',function($slug){
            $return = JointVenture::withTrashed()->whereSlug($slug)->first();
            return $return;
/*            if($return){
                if(Auth::User()->access=="Root Level"){
                }else{
                    abort(403);
                }
            }else{
                abort(404);
            }*/
        });


        Route::bind('budgettopic',function($slug){
            $return= BudgetTopic::withTrashed()->whereSlug($slug)->first();
            return $return;

            if($return){
                if(Auth::User()->access=="Root Level"){
                    return $return;
                }else{
                    abort(403);
                }
            }else{
                abort(404);
            }
        });


        /* income */
        Route::bind('income',function($id){
            $return =Income::withTrashed()->whereId($id)->first();
            if($return){
                if(Auth::User()->access=="Root Level"){
                    return $return;
                }else{
                    abort(403);
                }
            }else{
                abort(404);
            }
        });

        /* expense */
        Route::bind('expense',function($id){
            $return= Expense::withTrashed()->whereId($id)->first();
            if($return){
                if(Auth::User()->access=="Root Level"){
                    return $return;
                }else{
                    abort(403);
                }
            }else{
                abort(404);
            }
        });

        /* ExpenditureTopic */
        Route::bind('expendituretopic',function($slug){
            $return =ExpenditureTopic::withTrashed()->whereSlug($slug)->first();
            if($return){
                if(Auth::User()->access=="Root Level"){
                    return $return;
                }else{
                    abort(403);
                }
            }else{
                abort(404);
            }
        });

        /* IncomeTopic */
        Route::bind('income-topic',function($slug){
            $return = IncomeTopic::withTrashed()->whereSlug($slug)->first();
            if($return){
                if(Auth::User()->access=="Root Level"){
                    return $return;
                }else{
                    abort(403);
                }
            }else{
                abort(404);
            }
        });

        /* LumpSumBudget */
        Route::bind('lumpsumbudget',function($id){
            $return =LumpSumBudget::withTrashed()->whereId($id)->first();
            return $return;
        });


        /* ImplementingMode */
        Route::bind('implementingmode',function($slug){
            $return =ImplementingMode::withTrashed()->whereSlug($slug)->first();
            if($return){
                if(Auth::User()->access=="Root Level"){
                    return $return;
                }else{
                    abort(403);
                }
            }else{
                abort(404);
            }
        });

        /* Allocation */
        Route::bind('allocation',function($id){
            $return= Allocation::withTrashed()->find($id);
            if($return){
                if(Auth::User()->access=="Root Level"){
                    return $return;
                }else{
                    abort(403);
                }
            }else{
                abort(404);
            }
        });
        //
        /* Vendor */
        Route::bind('vendor',function($slug){
            $return =Vendor::withTrashed()->whereSlug($slug)->first();
            if($return){
                if(Auth::User()->access=="Root Level"){
                    return $return;
                }else{
                    abort(403);
                }
            }else{
                abort(404);
            }
        });
        //

        /* Employee */
        Route::bind('employee',function($slug){
            $return= Employee::withTrashed()->whereSlug($slug)->first();
            if($return){
                if(Auth::User()->access=="Root Level"){
                    return $return;
                }else{
                    abort(403);
                }
            }else{
                abort(404);
            }
        });

        /* Salary Head */
        Route::bind('salaryhead',function($slug){
            $return =SalaryHead::withTrashed()->whereSlug($slug)->first();
            if($return){
                if(Auth::User()->access=="Root Level"){
                    return $return;
                }else{
                    abort(403);
                }
            }else{
                abort(404);
            }
        });

        /* Fund Store */
        Route::bind('fundstore',function($slug){
            $return= FundStore::withTrashed()->whereSlug($slug)->first();
            if($return){
                if(Auth::User()->access=="Root Level"){
                    return $return;
                }else{
                    abort(403);
                }
            }else{
                abort(404);
            }
        });



        /* Procurement */
        Route::bind('procurement',function($id){
            return Procurement::withTrashed()->find($id);
        });
        /* Project */
        Route::bind('project',function($id){
            $project= Project::withTrashed()->whereId($id)->first();
            if($project)
                return $project;
            else
                abort('404');
        });

        /* Progress */
        Route::bind('progress',function($id){
            return Progress::withTrashed()->whereid($id)->first();
        });


    }

    /**
     * Define the routes for the application.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();
        $this->mapWebRoutes();
        //
    }
    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::group([
            'middleware' => 'web',
            'namespace' => $this->namespace,
        ], function ($router) {
            require base_path('routes/web.php');
        });
    }
    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::group([
            'middleware' => 'api',
            'namespace' => $this->namespace,
            'prefix' => 'api',
        ], function ($router) {
            require base_path('routes/api.php');
        });
    }
}