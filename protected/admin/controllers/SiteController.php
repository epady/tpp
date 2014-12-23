<?php

/**
 * 
 * 后台首页控制器
 *
 * 验证用户是否拥有权限登录
 */
class SiteController extends Controller
{

    /**
     * 不需要权限即可访问的动作列表
     * 
     * @return [type] [description]
     */
    public function allowedActions()
    {
        return 'error, index, logout';
    }
    
 



    /**
     * 后台用户登录页
     *
     * 没有登录时要求登录，已登录时，跳转至后台主界面
     * 
     * @return [type] [description]
     */
    public function actionIndex()
    {
        $this->layout = false;
        /**
         * 用户没有登录
         */
        if( Yii::app()->user->isGuest )
        {
            // 登录模型
            $model = new LoginForm;

            if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
            {
                echo CActiveForm::validate($model);
                Yii::app()->end();
            }

            if(isset($_POST['LoginForm']))
            {
                $model->attributes=$_POST['LoginForm'];
                
                if( $model->validate() && $model->login() )
                {
                    $this->redirect(array('site/index'));
                }
            }
            
            $this->render('login',array('model'=>$model));
            Yii::app()->end();
        }else{

            $model = Administrator::model()->findByPk(Yii::app()->user->id);

            $data['user'] = $model;


            $menu = include(Yii::app()->basePath . '/admin/config/menu.php'); 
            
            $data['menu'] = $menu[$model->role];

            $this->render('index',$data);
        }

    }


    /**
     * 欢迎首页
     * 
     * @return [type] [description]
     */
    public function actionWelcome()
    {
        $model = Administrator::model()->findByPk(Yii::app()->user->id);

        if( $model->role == 'pay' && empty($model->pay_password) )
        {
            $this->redirect(array('/site/profile'));
        }

        // 统计正在队列的订单数
        $data['number'] = Order::model()->count('status IN (0,3)', array());


        // 视图
        switch ($model->role) {
            case 'pay':
                $viewName = 'pay';
                break;
            case 'pre':
                $viewName = 'pre';
                break;
            default:
                $viewName = 'welcome';
                break;
        }

        $data['user'] = $model;
            
        $this->render($viewName, $data);   
        
    }



    /**
     * 退出后台登录
     * 
     * @return [type] [description]
     */
    public function actionLogout()
    {
        Yii::app()->user->logout(false);
        $this->redirect(Yii::app()->homeUrl);
    }



    /**
     * 出错提示
     * 
     * @return [type] [description]
     */
    public function actionError()
    {
        if ($error = Yii::app()->errorHandler->error)
        {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }



    /**
     * 用户信息修改
     * 
     * @return [type] [description]
     */
    public function actionProfile()
    {
        $model = Administrator::model()->findByPk(Yii::app()->user->id);


        if( $model->role == 'pay' && empty($model->pay_password) )
        {
            $model->addError('pay_password','务必设置支付密码。');
        }

        if( isset($_POST['Administrator']) )
        {
            $password = $_POST['Administrator']['password'];
            $pay_pass = $_POST['Administrator']['pay_password'];
            unset($_POST['Administrator']['password']);
            unset($_POST['Administrator']['pay_password']);

            if( !empty($password) )
            {
                $model->password = md5(md5($password).$model->salt);
            }

            if( !empty($pay_pass) && $model->role == 'pay' )
            {
                $model->pay_password = md5(md5($pay_pass).$model->salt);
            }
            
            $model->attributes = $_POST['Administrator'];

            if( $model->validate() && $model->save() )
            {
                Yii::app()->user->setFlash('success','个人信息修改成功');
                $this->redirect(array('site/welcome'));
            }
        }
        $data['model'] = $model;
        
        $this->render('profile',$data);
    }


}