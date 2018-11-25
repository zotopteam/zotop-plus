<?php

namespace Modules\Content\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Core\Base\FrontController;
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
     * 预览
     *
     * @return Response
     */
    public function preview($id)
    {
        $this->content = Content::findOrFail($id);

        return $this->view($this->content->view);
    }

    /**
     * 详情 id
     *
     * @return Response
     */
    public function show($id)
    {
        $this->content = Content::findOrFail($id);

        return $this->view($this->content->view);
    }

    /**
     * 详情 slug
     *
     * @return Response
     */
    public function slug($slug)
    {
        $this->content = Content::where('slug', $slug)->firstOrFail();

        return $this->view($this->content->view);
    }           
}
