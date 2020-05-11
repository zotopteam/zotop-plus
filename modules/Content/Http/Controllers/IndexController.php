<?php

namespace Modules\Content\Http\Controllers;

use App\Support\Facades\Filter;
use App\Modules\Routing\FrontController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Content\Models\Content;

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
     * 搜索
     *
     * @return Response
     */
    public function search(Request $request)
    {
        if ($request->keywords) {
            $this->list = Content::publish()->searchIn('title,keywords,summary', $request->keywords)->sort()->paginate(20);
        } else {
            $this->list = collect([]);
        }

        return $this->view('content::search');
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
