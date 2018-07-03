all:
	cp fatturaPA_1.2_schema.json www/.
	cp fatturaPA_1.2.hbs www/.
	cp samples/IT01234567890_FPA01-js.json www/.
	mkdir -p www/js
	cp node_modules/@json-editor/json-editor/dist/jsoneditor.min.js www/js/.
	cp node_modules/handlebars/dist/handlebars.min.js www/js/.
