<?php
sleep(1);	
require("inc/config.php");
require("inc/fungsi.php");
require("inc/koneksi.php");
require("inc/class/paging.php");



$filenya = "index.php";
$juz = nosql($_REQUEST['juz']);
$kunci = cegah($_REQUEST['kunci']);
$suratkd = nosql($_REQUEST['suratkd']);
$suratnama = balikin($_REQUEST['suratnama']);
$suratnamax = cegah($_REQUEST['suratnama']);
$pageku = nosql($_REQUEST['pageku']);

mysql_query("SET character_set_results = 'utf8'");
mysql_query("character_set_client = 'utf8'");
mysql_query("character_set_connection = 'utf8'");
mysql_query("character_set_database = 'utf8'");




function format_arabic_number($number){
	$arabic_number = array('٠','١','٢','٣','٤','٥','٦','٧','٨','٩');
	$jum_karakter = strlen($number);
	$temp = "";
	for($i = 0; $i < $jum_karakter; $i++){
		$char = substr($number, $i, 1);
		$temp .= $arabic_number[$char];
	}
	return $temp;
}








//jika cari.........
if ($_POST['btnCRI'])
	{
	//ambil nilai
	$kunci = cegah($_POST['kunci']);
	
	//re-direct
	$ke = "$filenya?kunci=$kunci";
	xloc($ke);
	exit();	
	}
?>




<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Qur'an</title>

    <!-- Bootstrap core CSS -->
    <link href="template/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="template/css/blog-post.css" rel="stylesheet">

	
	<style>
	#bgku {
	    width: 100%;
	    height: 100%;
	    background-image: url('template/alquran.jpg');
	    background-size: cover;
	    border: 1px solid red;
	}
	

@font-face {
  font-family: 'Uthmani';
  src : url('<?php echo $sumber;?>/quran.otf') format('truetype');
}

	</style>




  </head>

  <body>


<?php

//require
require("inc/js/jumpmenu.js");
require("inc/js/number.js");
require("inc/js/swap.js");

?>
	<div id="bgku">


    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
      <div class="container">
        <a class="navbar-brand" href="#">Qur'an</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
          <ul class="navbar-nav ml-auto">
            <li class="nav-item active">
              <a class="nav-link" href="<?php echo $sumber;?>">BERANDA
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#juz">Juz</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#surat">Surat</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#cari">Cari</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <!-- Page Content -->
    <div class="container">

      <div class="row">

        <!-- Post Content Column -->
        <div class="col-lg-8">

          <!-- Comments Form -->
          <div class="card my-4">
            <div class="card-header">

			
<?php
//jika null
if ((empty($suratkd)) AND (empty($juz)) AND (empty($kunci)))
	{
	echo '<img src="template/alquran.jpg" width="200" class="img-thumbnail">
	<h1>Qur\'an</h1>';	
	}



//jika pencarian ///////////////////////////////////////////////////////////////////////////////
else if (!empty($kunci))
	{
	//query
	$limit = 30;
	
	$p = new Pager();
	$start = $p->findStart($limit);

	$sqlcount = "SELECT * FROM quranku ".
					"WHERE AyahText LIKE '%$kunci%' ".
					"ORDER BY round(SuraID) ASC, ".
					"round(VerseID) ASC, ". 
					"round(DatabaseID) ASC";
	$sqlresult = $sqlcount;

	$count = mysql_num_rows(mysql_query($sqlcount));
	$pages = $p->findPages($count, $limit);
	$result = mysql_query("$sqlresult LIMIT ".$start.", ".$limit);
	$target = "$sumber/index.php?kunci=$kunci";
	$pagelist = $p->pageList($_GET['page'], $pages, $target);
	$data = mysql_fetch_array($result);


	//jika ada
	if (!empty($count))
		{
		echo '<table width="100%" border="0" cellspacing="0" cellpadding="3">
		<tr bgcolor="grey">
		<td>
		<h3>Hasil Pencarian</h3>
		</td>
		</tr>
		</table>
		<br>
		<br>
	
		
		<table width="100%" border="0" cellspacing="0" cellpadding="3">';
	
	
		do
			{
			if ($warna_set ==0)
				{
				$warna = $warna01;
				$warna_set = 1;
				}
			else
				{
				$warna = $warna02;
				$warna_set = 0;
				}
	
			//nilai
			$nomer = $nomer + 1;
			$isuratkd = nosql($data['SuraID']);
			$iayatkd = nosql($data['VerseID']);
			
			
			//detail e arab
			$qkux = mysql_query("SELECT * FROM quranku ".
									"WHERE VerseID = '$iayatkd' ".
									"AND SuraID = '$isuratkd' ".
									"AND DatabaseID = '1'");
			$rkux = mysql_fetch_assoc($qkux);
			$ayatei = $rkux['AyahText'];
			$nourut = $rkux['VerseID'];
	
	
			//jika bukan alfatikhah
			if ($suratkd > 1)
				{
				//hilangkan bismillah...
				$search  = array('بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ');
				$replace = array('');		
				$ayate = str_replace($search, $replace, $ayatei);
				}
			else
				{
				$ayate = $ayatei;					
				}
	
			
			//detail e indonesia
			$qkux2 = mysql_query("SELECT * FROM quranku ".
									"WHERE VerseID = '$iayatkd' ".
									"AND SuraID = '$isuratkd' ".
									"AND DatabaseID = '68'");
			$rkux2 = mysql_fetch_assoc($qkux2);
			$ayate2 = $rkux2['AyahText'];
			
	
			$ayatku = format_arabic_number($nourut);


			//suratnya
			$qkuy = mysql_query("SELECT * FROM suratku ".
									"WHERE nourut = '$isuratkd'");
			$rkuy = mysql_fetch_assoc($qkuy);
			$kuy_nama = balikin($rkuy['nama']);
          
				
			echo "<tr valign=\"top\" bgcolor=\"$warna\" onmouseover=\"this.bgColor='$warnaover';\" onmouseout=\"this.bgColor='$warna';\">";
			echo '<td>
			<h1>'.$ayate.'</h1>
			<br>
			'.$ayate2.'
			<hr>
			</td>
			<td width="50" align="center">
			'.$kuy_nama.'
			<br>
			'.$ayatku.'
			</td>
	   		</tr>';
			}
		while ($data = mysql_fetch_assoc($result));
	
		echo '</table>
		
		<h3>'.$pagelist.'</h3>';
		}

	else
		{
		echo '<h3>Pencarian Tidak Ditemukan...</h3>';
		}
	}
	
	
	
	
	
	
	


//jika juz ////////////////////////////////////////////////////////////////////////////////////
else if (!empty($juz))
	{
	echo '<table width="100%" border="0" cellspacing="0" cellpadding="3">
	<tr bgcolor="grey">
	<td>
	<h1>Juz : '.$juz.'</h1>';
			
	//query
	$limit = 30;


	//jika juz
	if ($juz == 1)
		{
		$suratkd1 = 1;
		$ayatkd1 = 1;
		$suratkd2 = 2;
		$ayatkd2 = 141;
		}

	//jika juz
	else if ($juz == 2)
		{
		$suratkd1 = 2;
		$ayatkd1 = 142;
		$suratkd2 = 2;
		$ayatkd2 = 252;
		}

	//jika juz
	else if ($juz == 3)
		{
		$suratkd1 = 2;
		$ayatkd1 = 253;
		$suratkd2 = 3;
		$ayatkd2 = 92;
		}

	//jika juz
	else if ($juz == 4)
		{
		$suratkd1 = 3;
		$ayatkd1 = 93;
		$suratkd2 = 4;
		$ayatkd2 = 23;
		}
		
	//jika juz
	else if ($juz == 5)
		{
		$suratkd1 = 4;
		$ayatkd1 = 24;
		$suratkd2 = 4;
		$ayatkd2 = 147;
		}

	//jika juz
	else if ($juz == 6)
		{
		$suratkd1 = 4;
		$ayatkd1 = 148;
		$suratkd2 = 5;
		$ayatkd2 = 81;
		}
		
		
	//jika juz
	else if ($juz == 7)
		{
		$suratkd1 = 5;
		$ayatkd1 = 82;
		$suratkd2 = 6;
		$ayatkd2 = 110;
		}
		
		
	//jika juz
	else if ($juz == 8)
		{
		$suratkd1 = 6;
		$ayatkd1 = 111;
		$suratkd2 = 7;
		$ayatkd2 = 87;
		}
		
		
		
	//jika juz
	else if ($juz == 9)
		{
		$suratkd1 = 7;
		$ayatkd1 = 88;
		$suratkd2 = 8;
		$ayatkd2 = 40;
		}
		
		
	//jika juz
	else if ($juz == 10)
		{
		$suratkd1 = 8;
		$ayatkd1 = 41;
		$suratkd2 = 9;
		$ayatkd2 = 92;
		}
		
		
	//jika juz
	else if ($juz == 11)
		{
		$suratkd1 = 9;
		$ayatkd1 = 93;
		$suratkd2 = 11;
		$ayatkd2 = 5;
		}
		
		
	//jika juz
	else if ($juz == 12)
		{
		$suratkd1 = 11;
		$ayatkd1 = 6;
		$suratkd2 = 12;
		$ayatkd2 = 52;
		}
		
		
		
		
	//jika juz
	else if ($juz == 13)
		{
		$suratkd1 = 12;
		$ayatkd1 = 53;
		$suratkd2 = 14;
		$ayatkd2 = 52;
		}
		
		
		
	//jika juz
	else if ($juz == 14)
		{
		$suratkd1 = 15;
		$ayatkd1 = 1;
		$suratkd2 = 16;
		$ayatkd2 = 128;
		}
		
		
	//jika juz
	else if ($juz == 15)
		{
		$suratkd1 = 17;
		$ayatkd1 = 1;
		$suratkd2 = 18;
		$ayatkd2 = 74;
		}
		
		
	//jika juz
	else if ($juz == 16)
		{
		$suratkd1 = 18;
		$ayatkd1 = 75;
		$suratkd2 = 20;
		$ayatkd2 = 135;
		}
		
		
	//jika juz
	else if ($juz == 17)
		{
		$suratkd1 = 21;
		$ayatkd1 = 1;
		$suratkd2 = 22;
		$ayatkd2 = 78;
		}
		
		
		
		
	//jika juz
	else if ($juz == 18)
		{
		$suratkd1 = 23;
		$ayatkd1 = 1;
		$suratkd2 = 25;
		$ayatkd2 = 20;
		}
		
		
	//jika juz
	else if ($juz == 19)
		{
		$suratkd1 = 25;
		$ayatkd1 = 21;
		$suratkd2 = 27;
		$ayatkd2 = 55;
		}
		
		
	//jika juz
	else if ($juz == 20)
		{
		$suratkd1 = 27;
		$ayatkd1 = 56;
		$suratkd2 = 29;
		$ayatkd2 = 45;
		}
		
		
		
	//jika juz
	else if ($juz == 21)
		{
		$suratkd1 = 29;
		$ayatkd1 = 46;
		$suratkd2 = 33;
		$ayatkd2 = 30;
		}
		
		
		
	//jika juz
	else if ($juz == 22)
		{
		$suratkd1 = 33;
		$ayatkd1 = 31;
		$suratkd2 = 36;
		$ayatkd2 = 27;
		}
		
		
	//jika juz
	else if ($juz == 23)
		{
		$suratkd1 = 36;
		$ayatkd1 = 28;
		$suratkd2 = 39;
		$ayatkd2 = 31;
		}
		
		
	//jika juz
	else if ($juz == 24)
		{
		$suratkd1 = 39;
		$ayatkd1 = 32;
		$suratkd2 = 41;
		$ayatkd2 = 46;
		}
		
		
	//jika juz
	else if ($juz == 25)
		{
		$suratkd1 = 41;
		$ayatkd1 = 47;
		$suratkd2 = 45;
		$ayatkd2 = 37;
		}
		
		
	//jika juz
	else if ($juz == 26)
		{
		$suratkd1 = 46;
		$ayatkd1 = 1;
		$suratkd2 = 51;
		$ayatkd2 = 30;
		}
		
	//jika juz
	else if ($juz == 27)
		{
		$suratkd1 = 51;
		$ayatkd1 = 31;
		$suratkd2 = 57;
		$ayatkd2 = 29;
		}
		
	//jika juz
	else if ($juz == 28)
		{
		$suratkd1 = 58;
		$ayatkd1 = 1;
		$suratkd2 = 66;
		$ayatkd2 = 12;
		}
		
	//jika juz
	else if ($juz == 29)
		{
		$suratkd1 = 67;
		$ayatkd1 = 1;
		$suratkd2 = 77;
		$ayatkd2 = 50;
		}
		
	//jika juz
	else if ($juz == 30)
		{
		$suratkd1 = 78;
		$ayatkd1 = 1;
		$suratkd2 = 114;
		$ayatkd2 = 6;
		}
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
			
	//detail surat
	$qkuy = mysql_query("SELECT * FROM suratku ".
							"WHERE nourut = '$suratkd1'");
	$rkuy = mysql_fetch_assoc($qkuy);
	$kuy_nama = balikin($rkuy['nama']);
          
	//detail surat
	$qkuy2 = mysql_query("SELECT * FROM suratku ".
							"WHERE nourut = '$suratkd2'");
	$rkuy2 = mysql_fetch_assoc($qkuy2);
	$kuy2_nama = balikin($rkuy2['nama']);
	
	echo '<b>'.$kuy_nama.' [ayat ke-'.$ayatkd1.' sampai '.$kuy2_nama.' [ayat ke-'.$ayatkd2.']</b>
	</td>
	</tr>
	</table>
	<br>';



	//query
	$p = new Pager();
	$start = $p->findStart($limit);

	$sqlcount = "SELECT * FROM quranku ".
					"WHERE DatabaseID = '1' ".
					"AND (round(SuraID) = '$suratkd1' ".
					"AND round(VerseID) >= '$ayatkd1') ".
					"UNION ".
					"SELECT * FROM quranku ".
					"WHERE DatabaseID = '1' ".
					"AND (round(SuraID) = '$suratkd2' ".
					"AND round(VerseID) <= '$ayatkd2') ".					
					"ORDER BY round(SuraID) ASC, ".
					"round(VerseID) ASC, ". 
					"round(DatabaseID) ASC";
	$sqlresult = $sqlcount;
	
	
	
	$count = mysql_num_rows(mysql_query($sqlcount));
	$pages = $p->findPages($count, $limit);
	$result = mysql_query("$sqlresult LIMIT ".$start.", ".$limit);
	$target = "$sumber/index.php?juz=$juz";
	$pagelist = $p->pageList($_GET['page'], $pages, $target);
	$data = mysql_fetch_array($result);



	//jika ada
	if (!empty($count))
		{
		echo '<table width="100%" border="0" cellspacing="0" cellpadding="3">';
	
	
		do
			{
			if ($warna_set ==0)
				{
				$warna = $warna01;
				$warna_set = 1;
				}
			else
				{
				$warna = $warna02;
				$warna_set = 0;
				}
	
			//nilai
			$nomer = $nomer + 1;
			$suratkd = nosql($data['SuraID']);
			$ayatkd = nosql($data['VerseID']);
			
			
			//detail e arab
			$qkux = mysql_query("SELECT * FROM quranku ".
									"WHERE VerseID = '$ayatkd' ".
									"AND SuraID = '$suratkd' ".
									"AND DatabaseID = '1'");
			$rkux = mysql_fetch_assoc($qkux);
			$ayatei = $rkux['AyahText'];
			$nourut = $rkux['VerseID'];
	
	

			//jika bukan alfatikhah
			if ($suratkd > 1)
				{
				//hilangkan bismillah...
				$search  = array('بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ');
				$replace = array('');		
				$ayate = str_replace($search, $replace, $ayatei);
				}
			else
				{
				$ayate = $ayatei;					
				}	
	
			
			//detail e indonesia
			$qkux2 = mysql_query("SELECT * FROM quranku ".
									"WHERE VerseID = '$ayatkd' ".
									"AND SuraID = '$suratkd' ".
									"AND DatabaseID = '68'");
			$rkux2 = mysql_fetch_assoc($qkux2);
			$ayate2 = $rkux2['AyahText'];
			
	
			$ayatku = format_arabic_number($nourut);
	
			echo "<tr valign=\"top\" bgcolor=\"$warna\" onmouseover=\"this.bgColor='$warnaover';\" onmouseout=\"this.bgColor='$warna';\">";
			echo '<td>
			<h1>'.$ayate.'</h1>
			<br>
			'.$ayate2.'
			<hr>
			</td>
			<td width="50" align="center">
			'.$ayatku.'
			</td>
	   		</tr>';
			}
		while ($data = mysql_fetch_assoc($result));
	
		echo '</table>
		
		<h3>'.$pagelist.'</h3>';
		}

	}


//jika surat ///////////////////////////////////////////////////////////////////////////////////
else if (!empty($suratkd))
	{
	//query
	$limit = 30;
	
	$p = new Pager();
	$start = $p->findStart($limit);

	$sqlcount = "SELECT DISTINCT(VerseID) AS ayatkd ".
					"FROM quranku ".
					"WHERE SuraID = '$suratkd' ".
					"ORDER BY round(VerseID) ASC, ". 
					"round(DatabaseID) ASC";
	$sqlresult = $sqlcount;

	$count = mysql_num_rows(mysql_query($sqlcount));
	$pages = $p->findPages($count, $limit);
	$result = mysql_query("$sqlresult LIMIT ".$start.", ".$limit);
	$target = "$sumber/index.php?suratkd=$suratkd";
	$pagelist = $p->pageList($_GET['page'], $pages, $target);
	$data = mysql_fetch_array($result);


	//jika ada
	if (!empty($count))
		{
		//detail surat
		$qkuy = mysql_query("SELECT * FROM suratku ".
								"WHERE nourut = '$suratkd'");
		$rkuy = mysql_fetch_assoc($qkuy);
		$kuy_nama = balikin($rkuy['nama']);
		$kuy_arti = balikin($rkuy['arti']);
		$kuy_jml = nosql($rkuy['jml_ayat']);
		
		echo '<table width="100%" border="0" cellspacing="0" cellpadding="3">
		<tr bgcolor="grey">
		<td>
		<h1>'.$kuy_nama.'</h1>
		['.$kuy_arti.'. '.$kuy_jml.' Ayat]
		</td>
		</tr>
		</table>
		<br>
		<br>
	
		
		<table width="100%" border="0" cellspacing="0" cellpadding="3">';
	
	
		do
			{
			if ($warna_set ==0)
				{
				$warna = $warna01;
				$warna_set = 1;
				}
			else
				{
				$warna = $warna02;
				$warna_set = 0;
				}
	
			//nilai
			$nomer = $nomer + 1;
			$ayatkd = nosql($data['ayatkd']);
			
			
			//detail e arab
			$qkux = mysql_query("SELECT * FROM quranku ".
									"WHERE VerseID = '$ayatkd' ".
									"AND SuraID = '$suratkd' ".
									"AND DatabaseID = '1'");
			$rkux = mysql_fetch_assoc($qkux);
			$ayatei = $rkux['AyahText'];
			$nourut = $rkux['VerseID'];
	
	
			//jika bukan alfatikhah
			if ($suratkd > 1)
				{
				//hilangkan bismillah...
				$search  = array('بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ');
				$replace = array('');		
				$ayate = str_replace($search, $replace, $ayatei);
				}
			else
				{
				$ayate = $ayatei;					
				}
	
			
			//detail e indonesia
			$qkux2 = mysql_query("SELECT * FROM quranku ".
									"WHERE VerseID = '$ayatkd' ".
									"AND SuraID = '$suratkd' ".
									"AND DatabaseID = '68'");
			$rkux2 = mysql_fetch_assoc($qkux2);
			$ayate2 = $rkux2['AyahText'];
			
	
			$ayatku = format_arabic_number($nourut);
	
			echo "<tr valign=\"top\" bgcolor=\"$warna\" onmouseover=\"this.bgColor='$warnaover';\" onmouseout=\"this.bgColor='$warna';\">";
			echo '<td>
			<h1>'.$ayate.'</h1>
			<br>
			'.$ayate2.'
			<hr>
			</td>
			<td width="50" align="center">
			'.$ayatku.'
			</td>
	   		</tr>';
			}
		while ($data = mysql_fetch_assoc($result));
	
		echo '</table>
		
		<h3>'.$pagelist.'</h3>';
		}

	}
?>


			</div>

          </div>


        </div>

        <!-- Sidebar Widgets Column -->
        <div class="col-md-4">
			<a name="cari"></a>
          <!-- Search Widget -->
          <div class="card my-4">
            <h5 class="card-header">Cari</h5>
            <div class="card-body">
			<form action="<?php echo $filenya;?>" method="post" name="formx">
				<div class="input-group">
	                <input name="kunci" type="text" class="form-control" placeholder="Ayat Yang Dicari...">
	                <span class="input-group-btn">            	
					<input name="btnCRI" type="submit" value="CARI >>" class="btn btn-secondary">
	                </span>
	            </div>    
	           </form>
                
            </div>
          </div>

          <!-- Categories Widget -->
          
			<a name="juz"></a>
          <div class="card my-4">
            <h5 class="card-header">Juz</h5>
            <div class="card-body">
			<?php
           	echo "<select name=\"katcari2\" onChange=\"MM_jumpMenu('self',this,0)\">";
			echo '<option value="'.$filenya.'?juz='.$juz.'&kunci='.$kunci.'" selected>'.$juz.'</option>';
			
			for ($k=1;$k<=30;$k++)
				{				
				echo '<option value="'.$filenya.'?juz='.$k.'">'.$k.'</option>';
				}	   
		   ?>
			</select>		   
            </div>
          </div>

			<a name="surat"></a>
          <!-- Side Widget -->
          <div class="card my-4">
            <h5 class="card-header">Surat</h5>
            <div class="card-body">
           <?php
			//detail surat
			$qkuy = mysql_query("SELECT * FROM suratku ".
									"WHERE nourut = '$suratkd'");
			$rkuy = mysql_fetch_assoc($qkuy);
			$kuy_nama = balikin($rkuy['nama']);
	           
			              
           	echo "<select name=\"katcari\" onChange=\"MM_jumpMenu('self',this,0)\">";
			echo '<option value="'.$filenya.'?suratkd='.$suratkd.'&suratnama='.$suratnamax.'&kunci='.$kunci.'" selected>'.$suratkd.' '.$kuy_nama.'</option>';
			
			//daftar surat
			$qku = mysql_query("SELECT * FROM suratku ".
									"ORDER BY round(nourut) ASC");
			$rku = mysql_fetch_assoc($qku);
			
			do
				{
				$ku_nourut = nosql($rku['nourut']);
				$ku_nama = trim(balikin($rku['nama']));
				$ku_namax = cegah(trim($rku['nama']));
				
				//detail surat
				$qkuy = mysql_query("SELECT * FROM suratku ".
										"WHERE nourut = '$ku_nourut'");
				$rkuy = mysql_fetch_assoc($qkuy);
				$kuy_jml = nosql($rkuy['jml_ayat']);
				
				echo '<option value="'.$filenya.'?suratkd='.$ku_nourut.'&suratnamax='.$ku_namax.'">'.$ku_nourut.' '.$ku_nama.'</option>';
				}
			while ($rku = mysql_fetch_assoc($qku));	   
		   ?>
            </div>
          </div>

        </div>

      </div>
      <!-- /.row -->

    </div>
    <!-- /.container -->


    <!-- Footer -->
    <footer class="py-5 bg-dark">
      <div class="container">
        <p class="m-0 text-center text-white">Copyright &copy; 2018. Qur'an</p>
      </div>
      <!-- /.container -->
    </footer>


	</div>


    <!-- Bootstrap core JavaScript -->
    <script src="template/vendor/jquery/jquery.min.js"></script>
    <script src="template/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>



  </body>

</html>


<?php
exit();
?>
