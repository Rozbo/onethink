<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2013 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------
namespace Admin\Controller;
/**
 * 后台分类管理控制器
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */

class CategoryController extends AdminController {

    static protected $allow = array( 'tree');
    /**
     * 左侧导航节点定义
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    static protected $nodes = array(
        
        /* 导航栏目设置 */
        array( 'title' => '分类管理', 'url' => 'Category/index', 'group' => '导航栏目设置',
            'operator'=>array(
                array('title'=>'编辑','url'=>'Category/edit','tip'=>'新增编辑和保存栏目分类'),
            ),
        ),
    );

    /**
     * 分类管理列表
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function index(){
        $tree = D('Category')->getTree();
        $this->assign('tree', $tree);
        C('_SYS_GET_CATEGORY_TREE_', true); //标记系统获取分类树模板
        $this->display();
    }

    /**
     * 显示分类树，仅支持内部调
     * @param  array $tree 分类树
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function tree($tree = null){
        C('_SYS_GET_CATEGORY_TREE_') || $this->_empty();
        $this->assign('tree', $tree);
        $this->display('tree');
    }

    /* 编辑分类 */
    public function edit($id = null, $pid = 0){
        $Category = D('Category');

        if(IS_POST){ //提交表单
            if(false !== $Category->update()){
                $this->success('编辑成功！', U('index'));
            } else {
                $error = $Category->getError();
                $this->error(empty($error) ? '未知错误！' : $error);
            }
        } else {
            $cate = '';
            if($pid){
                /* 获取上级分类信息 */
                $cate = $Category->info($pid, 'id,name,title,status');
                if(!($cate && 1 == $cate['status'])){
                    $this->error('指定的上级分类不存在或被禁用！');
                }
            }

            /* 获取分类信息 */
            $info = $id ? $Category->info($id) : '';

            $this->assign('info', $info);
            $this->assign('category', $cate);
            $this->display();
        }
    }

    /* 新增分类 */
    public function add($pid = 0){
        $Category = D('Category');

        if(IS_POST){ //提交表单
            if(false !== $Category->update()){
                $this->success('新增成功！', U('index'));
            } else {
                $error = $Category->getError();
                $this->error(empty($error) ? '未知错误！' : $error);
            }
        } else {
            $cate = array();
            if($pid){
                /* 获取上级分类信息 */
                $cate = $Category->info($pid, 'id,name,title,status');
                if(!($cate && 1 == $cate['status'])){
                    $this->error('指定的上级分类不存在或被禁用！');
                }
            }

            /* 获取分类信息 */
            $this->assign('category', $cate);
            $this->display('edit');
        }
    }
}
