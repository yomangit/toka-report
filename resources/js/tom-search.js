import TomSelect from 'tom-select';

new TomSelect("#user-select",{
	create: true,
	sortField: {
		field: "text",
		direction: "asc"
	}
});