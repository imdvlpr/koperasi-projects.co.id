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
		<h3 class="box-title">Cetak Laporan Buku Besar</h3>
		<div class="box-tools pull-right">
			<button class="btn btn-primary btn-sm" data-widget="collapse">
				<i class="fa fa-minus"></i>
			</button>
		</div>
	</div>
	<div class="box-body">
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
					<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-print" plain="false" onclick="cetak()">Cetak Laporan</a>

					<a href="javascript:void(0);" class="easyui-linkbutton" iconCls="icon-clear" plain="false" onclick="clearSearch()">Hapus Filter</a>
				</td>
			</tr>
		</table>
	</form>
</div>
</div>

<div class="box box-primary">
<div class="box-body">
<p></p>
<p style="text-align:center; font-size: 15pt; font-weight: bold;"> Laporan Buku Besar Periode <?php echo $tgl_periode_txt; ?></p>

<?php
$total_saldo = 0;
foreach ($nama_kas as $key) {
	$transD = $this->lap_buku_besar_m->get_transaksi_kas($key->id);

	echo '<h3>'.$key->nama.'</h3>';
	echo '<table  class="table table-bordered">
				<tr class="header_kolom">
					<th class="h_tengah" style="width:5%; vertical-align: middle "> No</th>
					<th class="h_tengah" style="width:10%; vertical-align: middle "> Tanggal </th>
					<th class="h_tengah" style="width:20%; vertical-align: middle "> Jenis Transaksi</th>
					<th class="h_tengah" style="width:45%; vertical-align: middle "> Keterangan </th>
					<th class="h_tengah" style="width:10%; vertical-align: middle "> Debet </th>
					<th class="h_tengah" style="width:10%; vertical-align: middle "> Kredit </th>
					<th class="h_tengah" style="width:10%; vertical-align: middle "> Saldo </th>
				</tr>';
	$jmlD = 0;
	$jmlk = 0;
	$no = 1;
	$saldo = 0;
	foreach ($transD as $rows) {
		$nm_akun = $this->lap_buku_besar_m->get_nama_akun_id($rows->transaksi);
		$tglD = explode(' ', $rows->tgl);
		$txt_tanggalD = jin_date_ina($tglD[0],'p');

		if($rows->dari_kas == $key->id) {
			$jmlk += $rows->kredit;
			$rows->debet = 0;
		}
		if($rows->untuk_kas == $key->id) {
			$jmlD += $rows->debet;
			$rows->kredit = 0;
		}
		$saldo = $jmlD - $jmlk;
		echo '<tr>
					<td class="h_tengah"> '.$no++.' </td>
					<td class="h_tengah"> '.$txt_tanggalD.' </td>
					<td> '.@$nm_akun->jns_trans.'</td>
					<td> '.$rows->ket.'</td>
					<td class="h_kanan"> '.number_format(nsi_round($rows->debet)).' </td>
					<td class="h_kanan"> '.number_format(nsi_round($rows->kredit)).' </td>
					<td class="h_kanan"> '.number_format(nsi_round($saldo )).' </td>
				</tr>';
	}
	$total_saldo += $saldo;
		echo '</table>';
	}
	echo '<br><table  class="table table-bordered">
				<tr class="header_kolom">
					<td class="h_kanan">TOTAL SALDO KAS BANK</td>
					<td class="h_kanan">'.number_format(nsi_round($total_saldo)).'</td>
					
				</tr>
			</table>
	';
?>
</div>
</div>

<script type="text/javascript">
$(document).ready(function() {
	fm_filter_tgl();
}); // ready

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

function doSearch() {
	var tgl_dari = $('input[name=daterangepicker_start]').val();
	var tgl_samp = $('input[name=daterangepicker_end]').val();
	$('input[name=tgl_dari]').val(tgl_dari);
	$('input[name=tgl_samp]').val(tgl_samp);
	$('#fmCari').attr('action', '<?php echo site_url('lap_buku_besar'); ?>');
	$('#fmCari').submit();	
}
function clearSearch(){
	window.location.href = '<?php echo site_url("lap_buku_besar"); ?>';
}

function cetak () {
	var tgl_dari = $('input[name=daterangepicker_start]').val();
	var tgl_samp = $('input[name=daterangepicker_end]').val();
	//$('input[name=tgl_dari]').val(tgl_dari);
	//$('input[name=tgl_samp]').val(tgl_samp);
	//$('#fmCari').attr('action', '<?php echo site_url('lap_buku_besar/cetak'); ?>');
	//$('#fmCari').submit();

	var win = window.open('<?php echo site_url("lap_buku_besar/cetak/?tgl_dari=' + tgl_dari + '&tgl_samp=' + tgl_samp + '"); ?>');
	if (win) {
		win.focus();
	} else {
		alert('Popup jangan di block');
	}
}
</script>