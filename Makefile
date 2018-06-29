all:
	mkdir -p www/js
	cp node_modules/@json-editor/json-editor/dist/jsoneditor.min.js www/js/.
	cp node_modules/handlebars/dist/handlebars.min.js www/js/.
