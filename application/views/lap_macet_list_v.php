<!-- Styler -->
<style type="text/css">
.panel * {
	font-family: "Arial","​Helvetica","​sans-serif";
}
.fa {
	font-family: "FontAwesome";
}
.datagrid-header-row * {
	font-weight: bold;
}
.messager-window * a:focus, .messager-window * span:focus {
	color: blue;
	font-weight: bold;
}
.daterangepicker * {
	font-family: "Source Sans Pro","Arial","​Helvetica","​sans-serif";
	box-sizing: border-box;
}
.glyphicon	{font-family: "Glyphicons Halflings"}

.form-control {
	height: 20px;
	padding: 4px;
}	
</style>

<?php

if(isset($_REQUEST['tgl_dari']) && isset($_REQUEST['tgl_samp'])) {
		$tgl_dari = $_REQUEST['tgl_dari'];
		$tgl_samp = $_REQUEST['tgl_samp'];
	} else {
		$tgl_dari = date('Y') . '-01-01';
		$tgl_samp = date('Y') . '-12-31';
	}
	$tgl_dari_txt = jin_date_ina($tgl_dari, 'p');
	$tgl_samp_txt = jin_date_ina($tgl_samp, 'p');
	$tgl_periode_txt = $tgl_dari_txt . ' - ' . $tgl_samp_txt;
?>

<div class="box box-solid box-primary">
	<div class="box-header">
		<h3 class="box-title">Cetak Laporan Kredit Macet</h3>
		<div class="box-tools pull-right">
			<button class="btn btn-primary btn-sm" data-widget="collapse">
				<i class="fa fa-minus"></i>
			</button>
		</div>
	</div>
	<div class="box-body">
	<div>
		<form id="fmCari" method="GET">
			<input type="hidden" name="tgl_dari" id="tgl_dari">
			<input type="hidden" name="tgl_samp" id="tgl_samp">
			<table>
				<tr>
					<td>
						<div id="filter_tgl" class="input-group" style="display: inline;">
							<button class="btn btn-default" id="daterange-btn">
								<i class="fa fa-calendar"></i> <span id="reportrange"><span><?php echo $tgl_periode_txt; ?>
								</span></span>
								<i class="fa fa-caret-down"></i>
							</button>
						</div>
					</td>
					<td>
						<input id="jenis_anggota_id" name="jenis_anggota_id" value="" style="width:200px; height:25px" class="">

						<input id="anggota_id" name="anggota_id" value="" style="width:200px; height:25px" class="">
					</td>
					<td>
						<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-print" plain="false" onclick="cetak()">Cetak Laporan</a>

						<a href="javascript:void(0);" class="easyui-linkbutton" iconCls="icon-clear" plain="false" onclick="clearSearch()">Hapus Filter</a>
					</td>
				</tr>
			</table>
		</form>
	</div>
</div>
</div>

<div class="box box-primary">
<div class="box-body">
<p style="text-align:center; font-size: 15pt; font-weight: bold;"> Laporan Kredit Macet Periode <?php echo $tgl_periode_txt; ?> </p>
	<table  class="table table-bordered">
		<tr class="header_kolom">
			<th style="width:5%; vertical-align: middle; text-align:center" > No. </th>
			<th style="width:10%; vertical-align: middle; text-align:center">Kode Pinjam</th>
			<th style="width:15%; vertical-align: middle; text-align:center">Nama Anggota</th>
			<th style="width:15%; vertical-align: middle; text-align:center"> Tanggal Pinjam  </th>
			<th style="width:15%; vertical-align: middle; text-align:center"> Tanggal Tempo  </th>
			<th style="width:10%; vertical-align: middle; text-align:center"> Lama Pinjam  </th>
			<th style="width:10%; vertical-align: middle; text-align:center"> Jumlah Tagihan  </th>
			<th style="width:10%; vertical-align: middle; text-align:center"> Dibayar  </th>
			<th style="width:10%; vertical-align: middle; text-align:center"> Sisa Tagihan  </th>
		</tr>
	<?php
	$no = $offset + 1;
	$jml_tagihan = 0;
	$jml_dibayar = 0;
	$jml_sisa = 0;
	
	foreach ($data_tempo as $rows) {
	if(($no % 2) == 0) {
		$warna="#eeeeee"; } 
	else {
		$warna="#FFFFFF"; }
		$tgl_pinjam = explode(' ', $rows->tgl_pinjam);
		$tgl_pinjam = jin_date_ina($tgl_pinjam[0],'p');

		$tgl_tempo = explode(' ', $rows->tempo);
		$tgl_tempo = jin_date_ina($tgl_tempo[0],'p');

		$jml_bayar = $this->general_m->get_jml_bayar($rows->id); 
		$jml_denda = $this->general_m->get_jml_denda($rows->id); 
		$total_tagihan = $rows->tagihan + $jml_denda->total_denda;
		$sisa_tagihan = $total_tagihan - $jml_bayar->total;

		$jml_tagihan += $total_tagihan;
		$jml_dibayar += $jml_bayar->total;
		$jml_sisa += $sisa_tagihan;

	echo '<tr bgcolor='.$warna.'>
				<td class="h_tengah">'.$no++.'</td>
				<td class="h_tengah">'.'TPJ' . sprintf('%05d', $rows->id) .'</td>
				<td class="h_kiri">'.$rows->nama.'</td>
				<td class="h_tengah">'.$tgl_pinjam.'</td>
				<td class="h_tengah">'.$tgl_tempo.'</td>
				<td class="h_tengah">'.$rows->lama_angsuran.' Bulan</td>
				<td class="h_kanan">'.number_format(nsi_round($total_tagihan)).'</td>
				<td class="h_kanan">'.number_format(nsi_round($jml_bayar->total)).'</td>
				<td class="h_kanan">'.number_format(nsi_round($sisa_tagihan)).'</td>
			</tr>';
	}
	echo '<tr class="header_kolom">
				<td colspan="6" class="h_tengah"><strong>Jumlah Total</strong></td>
				<td class="h_kanan"><strong>'.number_format(nsi_round($jml_tagihan)).'</strong></td>
				<td class="h_kanan"><strong>'.number_format(nsi_round($jml_dibayar)).'</strong></td>
				<td class="h_kanan"><strong>'.number_format(nsi_round($jml_sisa)).'</strong></td>
			</tr>';
	echo '</table>
		<div class="box-footer">'.$halaman.'</div>';
	?>
</div>
</div>
	
<script type="text/javascript">
$(document).ready(function() {
	fm_filter_tgl();
	
	<?php 
		if(isset($_REQUEST['anggota_id'])) {
			echo 'var anggota_id = "'.$_REQUEST['anggota_id'].'";';
		} else {
			echo 'var anggota_id = "";';
		}
		echo '$("#anggota_id").val(anggota_id);';
		
		if(isset($_REQUEST['jenis_anggota_id'])) {
			echo 'var jenis_anggota_id = "'.$_REQUEST['jenis_anggota_id'].'";';
		} else {
			echo 'var jenis_anggota_id = "";';
		}
		echo '$("#jenis_anggota_id").val(jenis_anggota_id);';
	?>

		
	$('#jenis_anggota_id').combogrid({
		panelWidth:300,
		url: '<?php echo site_url('lap_anggota/list_anggota'); ?>',
		idField:'id',
		valueField:'id',
		textField:'nama',
		mode:'remote',
		fitColumns:true,
		columns:[[
			{field:'id', title:'ID', align:'center', width:15},
			{field:'nama',title:'Nama Anggota',align:'left',width:20}
		]],
		onSelect:function(record){
			show_anggota($('input[name=jenis_anggota_id]').val())
			$('input[name=anggota_id]').val('')
			doSearch();
		}
	});
	
	$('#anggota_id').combogrid({
		panelWidth:300,
		url: '<?php echo site_url('lap_shu_anggota/list_anggota'); ?>'+'/'+jenis_anggota_id ,
		idField:'id',
		valueField:'id',
		textField:'id_nama',
		mode:'remote',
		fitColumns:true,
		columns:[[
			{field:'photo',title:'Photo',align:'center',width:5},
			{field:'id',title:'ID', hidden: true},
			{field:'id_nama', title:'IDNama', hidden: true},
			{field:'kode_anggota', title:'ID', align:'center', width:15},
			{field:'nama',title:'Nama Anggota',align:'left',width:20}
		]],
		onSelect:function(record){
			doSearch()
		}
	});	
}); // ready

function show_anggota(id){
	$('#anggota_id').combogrid({
		panelWidth:300,
		url: '<?php echo site_url('lap_shu_anggota/list_anggota'); ?>'+'/'+id ,
		idField:'id',
		valueField:'id',
		textField:'id_nama',
		mode:'remote',
		fitColumns:true,
		columns:[[
			{field:'photo',title:'Photo',align:'center',width:5},
			{field:'id',title:'ID', hidden: true},
			{field:'id_nama', title:'IDNama', hidden: true},
			{field:'kode_anggota', title:'ID', align:'center', width:15},
			{field:'nama',title:'Nama Anggota',align:'left',width:20}
		]],
		onSelect:function(record){
			doSearch()
		}
	});
}

function fm_filter_tgl() {
	$('#daterange-btn').daterangepicker({
		ranges: {
			'Hari ini': [moment(), moment()],
			'Kemarin': [moment().subtract('days', 1), moment().subtract('days', 1)],
			'7 Hari yang lalu': [moment().subtract('days', 6), moment()],
			'30 Hari yang lalu': [moment().subtract('days', 29), moment()],
			'Bulan ini': [moment().startOf('month'), moment().endOf('month')],
			'Bulan kemarin': [moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')],
			'Tahun ini': [moment().startOf('year').startOf('month'), moment().endOf('year').endOf('month')],
			'Tahun kemarin': [moment().subtract('year', 1).startOf('year').startOf('month'), moment().subtract('year', 1).endOf('year').endOf('month')]
		},
		locale: 'id',
		showDropdowns: true,
		format: 'YYYY-MM-DD',
		<?php 
			if(isset($tgl_dari) && isset($tgl_samp)) {
				echo "
					startDate: '".$tgl_dari."',
					endDate: '".$tgl_samp."'
				";
			} else {
				echo "
					startDate: moment().startOf('year').startOf('month'),
					endDate: moment().endOf('year').endOf('month')
				";
			}
		?>
	},

	function (start, end) {
		doSearch();
	});
}

function clearSearch(){
	window.location.href = '<?php echo site_url("lap_macet"); ?>';
}

function doSearch() {
	var tgl_dari = $('input[name=daterangepicker_start]').val();
	var tgl_samp = $('input[name=daterangepicker_end]').val();
	var jenis_anggota_id = $('#jenis_anggota_id').val();
	var anggota_id = $('#anggota_id').val();
	$('input[name=tgl_dari]').val(tgl_dari);
	$('input[name=tgl_samp]').val(tgl_samp);
	$('#fmCari').attr('action', '<?php echo site_url('lap_macet'); ?>');
	$('#fmCari').submit();	
}

function cetak () {
	var tgl_dari = $('input[name=daterangepicker_start]').val();
	var tgl_samp = $('input[name=daterangepicker_end]').val();
	//$('input[name=tgl_dari]').val(tgl_dari);
	//$('input[name=tgl_samp]').val(tgl_samp);
	//$('#fmCari').attr('action', '<?php echo site_url('lap_trans_kas/cetak'); ?>');
	//$('#fmCari').submit();

	var win = window.open('<?php echo site_url("lap_macet/cetak/?tgl_dari=' + tgl_dari + '&tgl_samp=' + tgl_samp + '"); ?>');
	if (win) {
		win.focus();
	} else {
		alert('Popup jangan di block');
	}

}
</script>