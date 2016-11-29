<?php
/*+++++++++++++++++++++++++++++++++++++++
 *|  �������ļ�
 *+++++++++++++++++++++++++++++++++++++++
 *|  cache.class.php
 *+++++++++++++++++++++++++++++++++++++++
 */
class Cache {
    //���ݿ����
    var $db;
    //������ļ���
    var $cachefile;
	//�����ŵ�·��
	var $cachepath;
    //�������ݿ�������úͻ���·��
    function __construct(&$db,$cachepath) {
        $this->db = $db;
		$this->cachepath = $cachepath;
    }
    //���û����ŵ��ļ�
    function getfile($cachename) {
        $this->cachefile = $this->cachepath . $cachename . '.php';
    }
    //���뻺�����ֺ�ʱ�䣬���ػ����Ƿ����
    function isvalid($cachename, $cachetime) {
        //����ʱ��Ϊ0��������Ч
        if (0 == $cachetime)
            return true;
        //��ȡ�����Ƿ����
        $this->getfile($cachename);
        //���治�ɶ��򻺴����
        if (!is_readable($this->cachefile) || $cachetime < 0) {
            return false;
        }
        //���������״̬���ļ���Ϣ
        clearstatcache();
        //���ػ����Ƿ����
        return (time() - filemtime($this->cachefile)) < $cachetime;
    }

    //��ȡ����
    function read($cachename, $cachetime=0) {
        //���û����·��
        $this->getfile($cachename);
        //�жϻ����Ƿ����
        if ($this->isvalid($cachename, $cachetime)) {
            return @include $this->cachefile;
        }
        return false;
    }

    //д�뻺��
    function write($cachename, $arraydata) {
        //���û���·��
        $this->getfile($cachename);
        //�жϻ��������Ƿ�Ϊ����
        if (!is_array($arraydata))
            return false;
        //ƴ��д���ļ�������
        $strdata = "<?php\nreturn " . var_export($arraydata, true) . ";\n?>";
        //������д���ļ�
        $bytes = writetofile($this->cachefile, $strdata);
        //�����ļ���С
        return $bytes;
    }
    //�������
    function remove($cachename) {
        //���û���·��
        $this->getfile($cachename);
        //����������������
        if (file_exists($this->cachefile)) {
            unlink($this->cachefile);
        }
    }

    //���ػ���
    function load($cachename, $id='id', $orderby='') {
        //��ȡ��������
        $arraydata = $this->read($cachename);
        //�����ȡ���Ϊ��������ݿ��л�ȡ
        if (!$arraydata) {
			//�������ͱ���һ��
            $sql = 'SELECT * FROM ' . $cachename;
            $orderby && $sql.=" ORDER BY $orderby ASC";
            $query = $this->db->query($sql);
            //ƴ�ӻ�������
            while ($item = $this->db->fetch_array($query)) {
                if (isset($item['k'])) {
                    $arraydata[$item['k']] = $item['v'];
                } else {
                    $arraydata[$item[$id]] = $item;
                }
            }
            //������д��
            $this->write($cachename, $arraydata);
        }
        //���ز�ѯ���
        return $arraydata;
    }

}

?>