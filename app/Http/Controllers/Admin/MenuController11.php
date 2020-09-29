<?php
namespace PMIS\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use PMIS\Http\Controllers\AdminBaseController;
use PMIS\Menu;

class MenuController11 extends AdminBaseController {
    protected $pro_data;
    protected $constructiontype;
    public function __construct(Menu $menu){
        parent::_construct();
        $this->pro_data['pageTitle'] = 'Menu';
        $this->pro_data['order']='asc';
        $this->menu = $menu;

    }
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $orderBy = 'id';
        $order = 'desc';
        $this->pro_data['order'] = 'asc';
        $this->pro_data['other_data'] = '';

        if(isset($_GET['search'])){
            $this->pro_data['menus'] = $this->menu->search($_GET['search'])->with('mainMenu.mainMenu','creator','updator');
            $this->pro_data['other_data'] = '&search='.$_GET['search'];
            if($this->pro_data['menus']->get()->isEmpty()){
                $this->pro_data['not_found']= 'Sorry! couldn\'t find your content. Please try with another keywords.';
            }
        }else{
            $this->pro_data['menus'] = $this->menu->with('mainMenu.mainMenu','creator','updator');
        }

        if(isset($_GET['orderBy']) && isset($_GET['order'])){
            $orderBy = $_GET['orderBy'];
            $order = $_GET['order'];
            $this->pro_data['order'] = (strtolower($_GET['order'])=='asc')?'desc':'asc';
        }
        $this->pro_data['trashes_no'] = $this->menu->onlyTrashed()->count();
        $this->pro_data['menus'] = $this->pro_data['menus']->where('level','>',0)->orderBy($orderBy,$order)->orderBy('updated_at','desc')->paginate(10);
        return view('admin/menu/index', $this->pro_data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        if($this->user_info->level >= 2){abort(403);}

        $this->pro_data['main_menu'] = $this->menu->whereLevel(0)->first();
        $this->pro_data['menus'] = $this->menu->where('level','>',0)->orderBy('order','asc')->get();
        return view('admin/menu/create', $this->pro_data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreateMenuRequest $request
     * @return Response
     */
    public function store(CreateMenuRequest $request)
    {
        if($this->user_info->level >= 2){abort(403);}

        $status = $request->get('status')=='on'?0:1;
        $this->menu->create([
            'name'=>$request->get('name'),
            'name_nep'=>$request->get('name_nep'),
            'description'=>$request->get('description'),
            'description_nep'=>$request->get('description_nep'),
            'category_id'=>$request->get('category_id'),
            'level'=>$request->get('level'),
            'order'=>$request->get('order'),
            'show_on_menu'=>1,
            'layout_id'=>Layout::whereStatus(0)->first()->id,
            'show_on_home_page'=>1,
            'page_category_order'=>$this->menu->whereStatus(0)->whereCategory_id($request->get('category_id'))->whereLevel($request->get('level'))->max('page_category_order')+1,
            'created_by'=>$this->user_info->id,
            'updated_by'=>$this->user_info->id,
            'status'=>$status,
        ]);
        $description=logDescriptionCreate($request->all());
        storeLog(null,$description,0 ,'Menu11');

        $this->session->flash('store_success_info','" menu named '.$request->get('name').'"');
        return redirect()->route('pro_admin.menu.create');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Menu $menu
     * @return Response
     */
    public function edit(Menu $menu)
    {
        if($this->user_info->level >= 2){abort(403);}
        $this->pro_data['menu_edit'] = $menu;
        $this->pro_data['main_menu'] = $this->menu->whereLevel(0)->first();
        $this->pro_data['menus'] = $this->menu->where('level','>',0)->orderBy('order','asc')->get();
        return view('admin/menu/edit', $this->pro_data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Menu $menu
     * @param  CreateMenuRequest $request
     * @return Response
     */
    public function update(CreateMenuRequest $request, Menu $menu)
    {
        if($this->user_info->level >= 2){abort(403);}
        $status = $request->get('status')=='on'?0:1;
        $oldMenu=$menu->toArray();

        $menu->fill([
            'name'=>$request->get('name'),
            'name_nep'=>$request->get('name_nep'),
            'description'=>$request->get('description'),
            'description_nep'=>$request->get('description_nep'),
            'order'=>$request->get('order'),
            'level'=>$request->get('level'),
            'category_id'=>$request->get('category_id'),
            'updated_by'=>$this->user_info->id,
            'status'=>$status,
        ])->save();

        $change=logDescriptionUpdate($menu, $oldMenu);
        if($change!=false){
            storeLog(null,$change,1 ,'Menu11');
        }

        $this->session->flash('update_success_info','" menu named '.$request->get('name').'"');
        return redirect()->route('pro_admin.menu.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Menu $menu
     * @return Response
     */
    public function destroy(Menu $menu)
    {
        if ($this->user_info->level >= 2) {
            abort(403);
        }

        $menuName = $menu->getAttribute('name');
        $menu->delete();

        foreach ($menu->subMenu as $m) {
            $m->delete();
        }

        storeLog(null,$menuName,2 ,'Menu11');
        $this->session->flash('delete_success_info','" menu named '.$menuName.'"');
        return redirect()->route('pro_admin.menu.index');
    }
}
