$('.datatable').DataTable({
	responsive: true,
	order: [[0, 'asc']],
	columns: [
		{data: 'ip', name: 'ip'},
		{data: 'type', name: 'type'},
		{data: 'comment', name: 'comment'},
		{
			data: 'actions',
			name: 'actions',
			orderable: false,
			searchable: false,
			class: 'text-center'
		}
	]
});
