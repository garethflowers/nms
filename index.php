<?php
require_once('lib/base.php');

PageHeader();
?>

<h1>Welcome to NMS Today</h1>

<table style="width:100%">
    <tr style="vertical-align:top">
        <td style="width:30%">

            <h2>Birthdays This Week</h2>

            <ul>
                <?php for ($c=0; $c<=7; $c++) {
                    $query = 'select dob,id,forename,surname,age_months(dob,current_date+'.$c.') as age';
                    $query .= ' from child';
                    $query .= ' where start<=current_date';
                    $query .= ' and finish>=current_date';
                    $query .= ' and extract(month from dob)=extract(month from current_date+'.$c.')';
                    $query .= ' and extract(day from dob)=extract(day from current_date+'.$c.')';
                    $data = Db::GetDataArray($query);

                    if (count($data)>0) { ?>
                <li class="none"><?php echo date('l, d-m-Y',mktime(0,0,0,date('m'),date('d')+$c,date('Y'))); ?>
                    <ul>
                                <?php foreach ($data as $row) { ?>
                        <li class="red none"><strong><?php echo $row['age']==0 ? 'Born Today!' : $row['age']/12; ?> -
                                <a href="child/view.php?i=<?php echo $row['id']; ?>"><?php echo $row['forename'].' '.$row['surname']; ?></a></strong>
                        </li>
                                <?php } ?>
                    </ul>
                </li>
                    <?php }
                } ?>
            </ul>

            <p>&nbsp;</p>

            <h2>New Child Session Query</h2>

            <form action="/reports/query.php" method="get" id="formreport">
                <table class="details">
                    <tr>
                        <th>Child Dob :</th>
                        <td><?php echo FormDate('d', '', ''); ?></td>
                    </tr>
                    <tr>
                        <th>Start Date :</th>
                        <td><?php echo FormDate('f', date('d-m-Y'), ''); ?></td>
                    </tr>
                    <tr>
                        <th>Finish Date :</th>
                        <td><?php echo FormDate('t', date('d-m-Y', mktime(1,1,1,date('m'),date('d'),date('Y')+1)), ''); ?></td>
                    </tr>
                    <tr>
                        <th>Mon :</th>
                        <td><?php echo FormCheck('mon_a', false, 'AM', ''); ?>
                        <?php echo FormCheck('mon_p', false, 'PM', ''); ?></td>
                    </tr>
                    <tr>
                        <th>Tue :</th>
                        <td><?php echo FormCheck('tue_a', false, 'AM', ''); ?>
                        <?php echo FormCheck('tue_p', false, 'PM', ''); ?></td>
                    </tr>
                    <tr>
                        <th>Wed :</th>
                        <td><?php echo FormCheck('wed_a', false, 'AM', ''); ?>
                        <?php echo FormCheck('wed_p', false, 'PM', ''); ?></td>
                    </tr>
                    <tr>
                        <th>Thu :</th>
                        <td><?php echo FormCheck('thu_a', false, 'AM', ''); ?>
                        <?php echo FormCheck('thu_p', false, 'PM', ''); ?></td>
                    </tr>
                    <tr>
                        <th>Fri :</th>
                        <td><?php echo FormCheck('fri_a', false, 'AM', ''); ?>
                        <?php echo FormCheck('fri_p', false, 'PM', ''); ?></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td><?php echo FormSubmit('process', 'Query Sessions', 'formreport'); ?></td>
                    </tr>
                </table>
            </form>
        </td>
        <td>

            <?php $day = GetFirstDayOfWeek(date('Y'),date('m'),date('d')); ?>
            <h2>This Weeks Numbers</h2>

            <table class="results">
                <tr>
                    <td colspan="2">Week beginning <?php echo date('d-m-Y',mktime(0,0,0,date('m'),$day,date('Y'))); ?></td>
                    <th>Monday</th>
                    <th>Tuesday</th>
                    <th>Wednesday</th>
                    <th>Thursday</th>
                    <th>Friday</th>
                </tr>
                <?php $data = Db::GetDataArray('select * from rooms order by min ASC');
                foreach ($data as $row) { ?>
                <tr class="hac">
                    <th rowspan="2">
                            <?php  echo '<a href="/reports/week_names.php?d='.date('d-m-Y').'&amp;r='.$row['id'].'">'.FormatText($row['name']).'</a>'; ?>
                        <br /><em>(<?php echo $row['total']; ?>)</em></th>
                    <th>AM</th>
                    <td><?php echo FormatTotals(SessionNumbers(date('d-m-Y',mktime(0,0,0,date('m'),$day,date('Y'))),'A',$row['id']),$row['total']); ?></td>
                    <td><?php echo FormatTotals(SessionNumbers(date('d-m-Y',mktime(0,0,0,date('m'),$day+1,date('Y'))),'A',$row['id']),$row['total']); ?></td>
                    <td><?php echo FormatTotals(SessionNumbers(date('d-m-Y',mktime(0,0,0,date('m'),$day+2,date('Y'))),'A',$row['id']),$row['total']); ?></td>
                    <td><?php echo FormatTotals(SessionNumbers(date('d-m-Y',mktime(0,0,0,date('m'),$day+3,date('Y'))),'A',$row['id']),$row['total']); ?></td>
                    <td><?php echo FormatTotals(SessionNumbers(date('d-m-Y',mktime(0,0,0,date('m'),$day+4,date('Y'))),'A',$row['id']),$row['total']); ?></td>
                </tr>
                <tr class="hac">
                    <th>PM</th>
                    <td><?php echo FormatTotals(SessionNumbers(date('d-m-Y',mktime(0,0,0,date('m'),$day,date('Y'))),'P',$row['id']),$row['total']); ?></td>
                    <td><?php echo FormatTotals(SessionNumbers(date('d-m-Y',mktime(0,0,0,date('m'),$day+1,date('Y'))),'P',$row['id']),$row['total']); ?></td>
                    <td><?php echo FormatTotals(SessionNumbers(date('d-m-Y',mktime(0,0,0,date('m'),$day+2,date('Y'))),'P',$row['id']),$row['total']); ?></td>
                    <td><?php echo FormatTotals(SessionNumbers(date('d-m-Y',mktime(0,0,0,date('m'),$day+3,date('Y'))),'P',$row['id']),$row['total']); ?></td>
                    <td><?php echo FormatTotals(SessionNumbers(date('d-m-Y',mktime(0,0,0,date('m'),$day+4,date('Y'))),'P',$row['id']),$row['total']); ?></td>
                </tr>
                <?php } ?>
            </table>

            <p>&nbsp;</p>

            <h2>Nursery Totals</h2>

            <ul>
                <li><?php $query = 'select count(id) from child where start<=now() and (finish>=now() or finish is null);';
                echo 'Total Current Children : <a href="/child">'.Db::GetScalar($query).'</a>'; ?></li>
                <li><?php $query = 'select count(id) from staff where start<=now() and (finish>=now() or finish is null);';
                echo 'Total Staff Members : <a href="/staff">'.Db::GetScalar($query).'</a>'; ?></li>
            </ul>
        </td>
    </tr>
</table>

<?php PageFooter(); ?>