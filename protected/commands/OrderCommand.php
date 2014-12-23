<?php

/**
 * 3小时检查一次，将订单结束3小时后的订单，标为已结束
 * 
 */
class OrderCommand extends CConsoleCommand
{

    private function log($msg)
    {
        // 创建当前日志文件  以日期命名
        $fileName = date('Y-m-d', time()) . '.log';
        // 存放目录
        $logpath = Yii::app()->runtimePath . '/log/order';

        if (!file_exists($logpath))
        {
            $ret = @mkdir($logpath, 0777, true);
            if (!$ret)
            {
                throw new Exception($logpath);
            }
        }
        $logfile = $logpath . '/' . $fileName;

        // 添加日志
        file_put_contents($logfile, date('Y-m-d H:i:s') . ' ' . $msg . "\n", FILE_APPEND);
    }


    /**
     * 订单列表
     * 
     * @param type $page
     * @return type
     */
    protected function getOrdersData($page=0)
    {
        $criteria = new CDbCriteria();
        $criteria->compare('status',4);
        $criteria->order = 'id ASC';
        $criteria->limit = 10;
        $criteria->offset = ($page - 1) * 10;
        $item = Order::model()->findAll($criteria);
        
        return $item;
    }    
    
    
    /**
     * 更新当前订单
     */
    public function actionIndex()
    {
        // 分页
        $itemCount = Order::model()->count('status = :status', array(':status'=>4));
        $pages = new CPagination($itemCount);
        $pages->pageSize = 10;

        for($i=1; $i <= $pages->pageCount; $i++)
        {
            $orders = $this->getOrdersData($i);
            foreach($orders as $item)
            {
				$updated = $item->updated;

				$lastTime = $updated + 60*60*24*3;

				if( $lastTime <= time() )
				{
					// 更新订单状态
					Order::model()->updateByPk($item->id, array('status'=>5,'updated'=>time()));

					// 更新用户状态
					Member::model()->updateByPk($item->member_id, array('order_id'=>0,'order_status'=>0));
					echo "Order_id:".$item->id." 更新完成 \r\n";
					
					
				}
				        	
            }
        }
    }
    
    
    
}