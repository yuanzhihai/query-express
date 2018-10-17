# think-query-express
The Think5.1 query-express
��ݲ�ѯ SDK
## ��װ

### һ��ִ�����װ
```
composer require yzh52521/think-query-express
```

����

### ����require��װ
```
"require": {
        "yzh52521/think-query-express":"*"
},
```

����
###  ����autoload psr-4��׼��װ
```
   a) ����vendor/dh2yĿ¼ (û��dh2yĿ¼ mkdir dh2y)
   b) git clone 
   c) �޸� git clone��������Ŀ����Ϊthink-query-express
   d) �����������
   "autoload": {
        "psr-4": {
            "yzh52521\\query\\express\\": "vendor/yzh52521/think-query-express/src"
        }
    },
    e) php composer.phar update
```


## ʹ��
#### ��������ļ����Ǳ��룩
```
 ��config/express.php ���Ƶ�����Ŀ¼���漴��
 
 1�������ȡ��ݹ�˾�Ǳ��룬��������������ӱ����Ӧ�Ŀ�ݹ�˾
 
```

#### ʹ�÷���

   ###### 1-1����ȡ��ݹ�˾��Ϣ
   
   ```
    $num = 'XXXXXXXX';
    $Query = QueryExpress::getInstance();
    
    $express = $Query->getType($num);
    
    
   ```
   ###### 1-2����ȡ��ݹ�˾��Ϣ������Ϣ
    
   ```
    array(3) {
      ["type"] => string(8) "shentong"
      ["num"] => int(221401186231)
      ["name"] => string(12) "��ͨ���"
    }
   ```
    
   ###### 2-1����ȡ�����Ϣ����
      
   ```
        $num = 'XXXXXXXX';
        $Query = QueryExpress::getInstance();
        
        $express = $Query->details($num);
        
     
   ```
   ###### 2-2����ȡ�����Ϣ������Ϣ
   >state 0����;��,1���ѷ�����2�����Ѽ���3�� ��ǩ�� ��4�����˻���
        
   ```
       array(6) {
         ["data"] => array(16) {
           [0] => array(4) {
             ["time"] => string(19) "2018-09-27 07:52:40"
             ["ftime"] => string(19) "2018-09-27 07:52:40"
             ["context"] => string(50) "������ƺ�ع�˾-�ѷ���-����������˾"
             ["location"] => string(0) ""
           }
           [1] => array(4) {
             ["time"] => string(19) "2018-09-26 20:19:12"
             ["ftime"] => string(19) "2018-09-26 20:19:12"
             ["context"] => string(79) "������ƺ�ع�˾-������ƺ�ع�˾(15591577188,0915-8287888)-���ռ�"
             ["location"] => string(0) ""
           }
         }
         ["type"] => string(8) "shentong"
         ["name"] => string(12) "��ͨ���"
         ["num"] => string(12) "221401186231"
         ["state"] => string(1) "3"
         ["ret"] => string(9) "��ǩ��"
       }
   ```
 ###### 3-1����ȡ���״̬
   
   ```
    $num = 'XXXXXXXX';
    $Query = QueryExpress::getInstance();
    
    $express = $Query->getState($num);
    
    
   ```
   ###### 3-2����ȡ���״̬������Ϣ
    
   ```
    array(2) {
      ["state"] => string(1) "3"
      ["ret"] => string(9) "��ǩ��"
    }
   ```
    
    

     


