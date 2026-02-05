<script>
    document.addEventListener('DOMContentLoaded', function() {

        let medicoSelecionado = '';

        const calendar = new FullCalendar.Calendar(
            document.getElementById('calendar'), {

                initialView: 'timeGridWeek',
                locale: 'pt-br',
                editable: true,
                selectable: true,
                slotMinTime: '07:00:00',
                slotMaxTime: '19:00:00',
                allDaySlot: false,
                nowIndicator: true,
                height: 'auto',

                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'timeGridDay,timeGridWeek,dayGridMonth'
                },

                slotDuration: '00:30:00',

                eventSources: [{
                        url: '/agenda/eventos'
                    },
                    {
                        url: '/bloqueios/eventos'
                    }
                ],

                dateClick(info) {
                    document.getElementById('data').value = info.dateStr.substring(0, 10);
                    document.getElementById('hora').value = info.dateStr.substring(11, 16) || '08:00';
                    new bootstrap.Modal(document.getElementById('novaConsultaModal')).show();
                }
            }
        );

        calendar.render();

        document.getElementById('filtro_medico').addEventListener('change', function() {
            medicoSelecionado = this.value;
            calendar.refetchEvents();
        });
    });
</script>