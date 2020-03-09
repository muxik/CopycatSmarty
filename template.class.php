<?php
/*
 * @Author: Muxi_k
 * @Date: 2020-03-09 15:27:38
 */

class template
{

    // 模板文件夹目录
    private $templateDir;

    // 编译文件目录
    private $compileDir;

    //模板文件中标记
    private $leftTag = '{#';
    private $rightTag = '#}';

    /**
     * 正在编译的模板文件名
     * @var string
     */
    private $currentTemp = '';

    /**
     * 正在编译中的html代码
     * @var string
     */
    private $outputHtml;

    /**
     * 变量池
     * @var array
     */
    private $varPool = [];


    public function __construct($templateDir, $compileDir, $leftTag = null, $rightTag = null)
    {
        $this->templateDir = $templateDir;
        $this->compileDir = $compileDir;
        // 判断标记是否为空
        if (!empty($leftTag)) $this->leftTag = $leftTag;
        if (!empty($rightTag)) $this->rightTag = $rightTag;
    }

    /**
     * @description: 把模板中的变量放到变量池中
     * @param $tag
     * @param $var 模板变量
     * @return:
     */
    public function assign($tag, $var)
    {
        $this->varPool[$tag] = $var;
    }

    /**
     * @description: 获取变量池的值
     * @param $tag 索引
     * @return: Array varPool
     */
    public function getVar($tag)
    {
        return $this->varPool[$tag];
    }

    /**
     * @description: 获取模板源文件
     * @param string $templateName 模板文件名
     * @param string $ext ='.html' 模板文件后缀
     * @return:
     */
    public function getSourceTemplate($templateName, $ext = '.html')
    {
        $this->currentTemp = $templateName;
        $sourceFilename = $this->templateDir . $this->currentTemp . $ext;
        $this->outputHtml = file_get_contents($sourceFilename);
    }

    /**
     * @description: 编译模板文件
     * @param null $templateName 模板文件名
     * @param string $ext ='.html' 模板文件后缀
     * @return:
     */
    public function compileTemplate($templateName = null, $ext = '.html')
    {
        $templateName = empty($templateName) ? $this->currentTemp : $templateName;
        $pattern = '/' . preg_quote($this->leftTag);
        $pattern .= ' *\$([a-zA-Z_]\w*) *';
        $pattern .= preg_quote($this->rightTag) . '/';


        $this->outputHtml = preg_replace($pattern, '<?php echo $this->getVar(\'$1\')?>', $this->outputHtml);
        $compiledFilename = $this->compileDir . md5($templateName)  . $ext;
        file_put_contents($compiledFilename, $this->outputHtml);
    }

    /**
     * @description: 显示模板文件
     * @param null $templateName
     * @param string $ext
     */
    public function display($templateName = null, $ext = '.html')
    {
        $templateName = empty($templateName) ? $this->currentTemp : $templateName;
        require_once $this->compileDir . md5($templateName) . $ext;
    }
}