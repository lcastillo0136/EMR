<?php

require_once 'renderer.php';
require_once 'components/utils/file_utils.php';

class ExcelRenderer extends Renderer
{
    function RenderPageNavigator($PageNavigator)
    { }

    function RenderDetailPageEdit($DetailPage)
    {
        $this->RenderPage($DetailPage);
    }
        
    function RenderPage($page)
    {
        ob_end_clean();
        if ($page->GetContentEncoding() != null)
            header('Content-type: application/vnd.ms-excel; charset=' . $page->GetContentEncoding());
        else
            header('Content-type: application/vnd.ms-excel;');
        $this->DisableCacheControl();
        header("Content-Disposition: attachment; filename=" .
            Path::ReplaceFileNameIllegalCharacters($page->GetCaption() . ".xls"));
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
        header("Pragma: public");
                             
        $grid = $this->Render($page->GetGrid());
        $this->DisplayTemplate('export/excel_page.tpl',
            array('Page' => $page),
            array('Grid' => $grid));
    }
    
    function PrepareForExcel($str)
    {
    	$ret = htmlspecialchars($str);
    	if (substr($ret,0,1)== "=") 
    		$ret = "&#61;".substr($ret,1);
    	return $ret;    
    }    
    
    function RenderGrid(Grid $Grid)
    {
        $Rows = array();
        $HeaderCaptions = array();
        $Grid->GetDataset()->Open();
        foreach($Grid->GetExportColumns() as $Column)
            $HeaderCaptions[] = $this->PrepareForExcel($Column->GetCaption());
        while($Grid->GetDataset()->Next())
        {
            $Row = array();
            foreach($Grid->GetExportColumns() as $Column)
            {
                $cell['Value'] = $this->PrepareForExcel($this->Render($Column));
                $cell['Align'] = $Column->GetAlign();
                $Row[] = $cell;
            }
            $Rows[] = $Row;
        }
            	
        $this->DisplayTemplate('export/excel_grid.tpl',
            array(
                'Grid' => $Grid
                ),
            array(
                'HeaderCaptions' => $HeaderCaptions,
                'Rows' => $Rows
            ));
    }
    
    // Column rendering
    protected function HttpHandlersAvailable() 
    { 
        return false; 
    }

    protected function HtmlMarkupAvailable() 
    { 
        return false; 
    }    
}
?>