module.exports = {
	query : `PREFIX schema: <http://schema.org/>
			#Names of Wikipedia articles in multiple languages
			SELECT DISTINCT ?lang ?name WHERE {
				?article schema:about wd:Q1.
				?article schema:inLanguage ?lang.
				?article schema:name ?name.
				FILTER(?lang IN("en", "uz", "ru", "ko", "it"))
				FILTER(!CONTAINS(?name, ":"))
			}
			LIMIT 10 `
}