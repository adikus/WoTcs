<table id='stats-info' class="tab-pane table table-bordered table-striped">
			
	<thead>
		<tr>
			<th>&nbsp;</th>
			<th stats-data="GPL/member_count">Battles</th>
			<th stats-data="WIN/GPL/0.01">Wins</th>
			<th stats-data="SUR/GPL/0.01">Survived</th>
			<th stats-data="FRG/GPL">Frags</th>
			<th stats-data="KD">Kill/death r.</th>
			<th stats-data="SPT/GPL">Spotted</th>
			<th stats-data="DMG/GPL">Damage</th>
			<th stats-data="CPT/GPL">Cap. pts.</th>
			<th stats-data="DPT/GPL">Def. pts.</th>
			<th stats-data="EXP/GPL">Experience</th>
			<th stats-data="EFR/member_count">Efficiency</th>
			<th stats-data="WN7/member_count">WN7</th>
			<th stats-data="GPL/member_count">Score</th>
			<?if(isset($isclan) && $isclan){?><th stats-data="member_count">Members</th><?}?>
		</tr>
	</thead>
	
	<tbody>
		<tr>
			<td>Total:</td>
			<td><span class="label"></span></td>
			<td></td>
			<td></td>
			<td></td>
			<td><span class="label"></span></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td><span class="label"></span></td>
			<td><span class="label"></span></td>
			<td><span class="label"></span></td>
			<?if(isset($isclan) && $isclan){?><td><span class="label"></span></td><?}?>
		</tr>
		<tr>
			<td>Per battle:</td>
			<td></td>
			<td><span class="label"></span></td>
			<td><span class="label"></span></td>
			<td><span class="label"></span></td>
			<td></td>
			<td><span class="label"></span></td>
			<td><span class="label"></span></td>
			<td><span class="label"></span></td>
			<td><span class="label"></span></td>
			<td><span class="label"></span></td>
			<td><?if(isset($isclan) && $isclan){?><span class="label"></span><?}?></td>
			<td><?if(isset($isclan) && $isclan){?><span class="label"></span><?}?></td>
			<td><?if(isset($isclan) && $isclan){?><span class="label"></span><?}?></td>
			<?if(isset($isclan) && $isclan){?><td></td><?}?>
		</tr>
		<tr>
			<td>Percentile:</td>
			<td><span class="label"></span></td>
			<td><span class="label"></span></td>
			<td><span class="label"></span></td>
			<td><span class="label"></span></td>
			<td><span class="label"></span></td>
			<td><span class="label"></span></td>
			<td><span class="label"></span></td>
			<td><span class="label"></span></td>
			<td><span class="label"></span></td>
			<td><span class="label"></span></td>
			<td><span class="label"></span></td>
			<td><span class="label"></span></td>
			<td><span class="label"></span></td>
			<?if(isset($isclan) && $isclan){?><td><span class="label"></span></td><?}?>
		</tr>
	</tbody>
</table>