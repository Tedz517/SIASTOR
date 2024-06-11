<script>
    $(document).ready(function() {
        var date = new Date();
        var d = date.getDate(),
            m = date.getMonth(),
            y = date.getFullYear();

        $('#calendar').fullCalendar({
            header: {
                left: 'prev,next',
                center: '',
                right: 'title'
            },
            events: [
                <?php
                foreach ($presence as $pre) {
                    if (preg_match('/TELAT/i', $pre['description']) == true) {
                        $desc = $pre['description'];
                        $color = '#ff0000';
                    } else if ($pre['description'] == 'IJIN') {
                        $desc = $pre['description'];
                        $color = '#f39c12';
                    } else if ($pre['description'] == 'CUTI') {
                        $desc = $pre['description'];
                        $color = '#00c0ef';
                    } else if ($pre['description'] == 'SAKIT') {
                        $desc = $pre['description'];
                        $color = '#d81b60';
                    } else if ($pre['description'] == 'DINAS LUAR' or $pre['description'] == 'MASUK') {
                        $desc = $pre['description'];
                        $color = '#00a65a';
                    } else {
                        $desc = $pre['description'];
                        $color = '#605ca8';
                    }
                ?> {
                        title: '<?php echo $desc; ?>',
                        start: '<?php echo $pre['transaction_date']; ?>',
                        // end: '<?php echo (isset($pre['thru_date']) ? date('Y-m-d', strtotime($pre['thru_date'] . ' + 1 DAY')) : ''); ?>',
                        backgroundColor: '<?php echo $color; ?>',
                        borderColor: '<?php echo $color; ?>'
                    },
                <?php } ?>
            ],
            editable: true,
            droppable: true,
            drop: function(date, allDay) {
                var originalEventObject = $(this).data('eventObject');
                var copiedEventObject = $.extend({}, originalEventObject);

                copiedEventObject.start = date;
                copiedEventObject.allDay = allDay;
                copiedEventObject.backgroundColor = $(this).css('background-color');
                copiedEventObject.borderColor = $(this).css('border-color');

                $('#calendar').fullCalendar('renderEvent', copiedEventObject, true);

                if ($('#drop-remove').is(':checked')) {
                    $(this).remove();
                }
            }
        });
    });
</script>