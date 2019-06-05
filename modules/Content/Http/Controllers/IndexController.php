<?php

namespace Modules\Content\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Core\Base\FrontController;
use Modules\Content\Models\Content;
use Filter;

class IndexController extends FrontController
{
    /**
     * 首页
     *
     * @return Response
     */
    public function index()
    {
        return $this->view();
    }

    /**
     * 首页
     *
     * @return Response
     */
    public function search(Request $request)
    {
        if ($request->keywords) {
            $this->list = Content::publish()->when($request->keywords, function($query, $keywords) {
                $query->where('title', 'like', '%'.$keywords.'%')->orWhere('keywords->;', 'like', '%'.$keywords.'%')->orWhere('title', 'like', '%'.$keywords.'%');
            })->sort()->paginate(20);
        } else {
            $this->list = collect([]);
        }

        return $this->view('search');
    }


    /**
     * 预览内容
     *
     * @return Response
     */
    public function preview($id)
    {
        $this->content = Content::findOrFail($id);

        return $this->view($this->content->view);
    }

    /**
     * 详情 通过id查找
     *
     * @return Response
     */
    public function show($id)
    {
        $this->content = Content::publish()->where('id', $id)->firstOrFail();
        $this->content = Filter::fire('content.show', $this->content);
        $this->title = $this->content->title;


        return $this->view($this->content->view);
    }

    /**
     * 详情 通过slug查找
     *
     * @return Response
     */
    public function slug($slug)
    {
        $this->content = Content::publish()->where('slug', $slug)->firstOrFail();
        $this->content = Filter::fire('content.show', $this->content);

        $this->title = $this->content->title;

        return $this->view($this->content->view);
    }           
}
