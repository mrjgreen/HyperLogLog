<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script src="http://code.highcharts.com/highcharts.js"></script>
<script src="http://code.highcharts.com/modules/exporting.js"></script>

<div style="width:300px; float:left;">
    <a href="../absolute/">Absolute</a> | <a href="../relative/">Relative</a> | <a href="../log/">LogLog Graph</a>
</div>

<div style="width:350px; float:left;">
    DataSet<br/>
    <select id="dataset" onchange="reloadPlot(this.value)">
        <option value="">-- Choose a Data Set --</option>
        <?php
        $folders = glob(__DIR__ . '/../data/*');
        foreach($folders as $folder){
            echo '<optgroup label="'.basename($folder).'">';

            foreach(glob($folder.'/*') as $file){

                $option = basename($folder).'/'.basename($file);
                echo '<option value="'.$option.'">';
                echo basename($file);
                echo '</option>';
            }

            echo '</optgroup>';
        }?>
    </select>
    <button onclick="reloadPlot(document.getElementById('dataset').value)">Refresh</button>
</div>

<div style="clear:both;margin-bottom: 10px;"></div>