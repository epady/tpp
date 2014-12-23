<?php
/**
 * 系统配置
 *
 * 将数据保存在数据库表中的配置
 * 
 */
class ConfigController extends Controller
{

	public function actionIndex()
	{
        if(isset($_POST['config']))
        {
            foreach($_POST['config'] as $key=>$value)
            {
                Yii::app()->config->set($key, $value);
            }
        }
		$this->render('index');
	}


    /**
     * 订单价格
     * 
     * @return [type] [description]
     */
    public function actionPrice()
    {
        if(isset($_POST['config']))
        {
            foreach($_POST['config'] as $key=>$value)
            {
                Yii::app()->config->set($key, $value);
            }
        }
        $this->render('price');
    }    


    /**
     * 短信设置
     * 
     * @return [type] [description]
     */
    public function actionSms()
    {
        if(isset($_POST['config']))
        {
            foreach($_POST['config'] as $key=>$value)
            {
                Yii::app()->config->set($key, $value);
            }
        }
        $this->render('sms');
    }    



    /**
     * APP版本管理
     * 
     * @return [type] [description]
     */
    public function actionApp()
    {
        if(isset($_POST['config']))
        {
            foreach($_POST['config'] as $key=>$value)
            {
                Yii::app()->config->set($key, $value);
            }
        }
        $this->render('app');
    }    


    /**
     * 新用户注册
     * 
     * @return [type] [description]
     */
    public function actionRegister()
    {
        if(isset($_POST['config']))
        {
            foreach($_POST['config'] as $key=>$value)
            {
                Yii::app()->config->set($key, $value);
            }
        }
        $this->render('register');
    }    

}