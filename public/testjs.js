function formatDate(date, type = 'en-US') {
	return new Date(date).toLocaleDateString(type, { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: 'numeric', minute: 'numeric'});
}

const params = process.argv;
// console.log(params);
console.log(formatDate(params[2], params[3]));