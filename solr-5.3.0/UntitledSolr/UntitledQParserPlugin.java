package org.apache.solr.search.untitledQueryParser;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;
import java.net.HttpURLConnection;
import java.net.URL;
import java.net.URLEncoder;

import org.apache.solr.common.params.DisMaxParams;
import org.apache.solr.common.params.ModifiableSolrParams;
import org.apache.solr.common.params.SolrParams;
import org.apache.solr.request.SolrQueryRequest;
import org.apache.solr.search.ExtendedDismaxQParser;
import org.apache.solr.search.ExtendedDismaxQParserPlugin;
import org.apache.solr.search.QParser;

import com.memetix.mst.language.Language;
import com.memetix.mst.translate.Translate;

import twitter4j.JSONException;
import twitter4j.JSONObject;

public class UntitledQParserPlugin extends ExtendedDismaxQParserPlugin {
	public static final String NAME = "UntitledQParserPlugin";
	private static final String[] CUSTOM_QF = { "tweet_text_en", "tweet_text_exact_en", "tweet_text_ru",
			"tweet_text_exact_ru", "tweet_text_de", "tweet_text_exact_de", "tweet_text_fr", "tweet_text_exact_fr",
			"tweet_text_ar", "tweet_text_exact_ar", "tweet_hashtags", "tweet_favorite_count", "tweet_retweet_count",
			"user_followers_count", "user_listed_count" };

	private final String USER_AGENT = "Mozilla/5.0";

	// Used 3rd party language detection API. See : https://detectlanguage.com/
	private String langDetectionUrl = "http://ws.detectlanguage.com/0.2/detect";
	private final String LANG_DETECTION_API_KEY = "7bd4928c9e29cc17138d3c810f39c3a8";

	// Used Windows Azure language translation API. See :
	// https://www.microsoft.com/en-us/translator/getstarted.aspx
	private final String langTranslationClientId = "IR_Project_B";
	private final String LANG_TRANSLATION_SECRET_KEY = "FPxC7f5ikFfzZm5EWqbYd5R4wvxB0niS1FhS3rYt16A=";
	private String charSet;

	@Override
	public QParser createParser(String originalQuery, SolrParams localParams, SolrParams params, SolrQueryRequest req) {
		ModifiableSolrParams customParams = new ModifiableSolrParams();
		charSet = java.nio.charset.StandardCharsets.UTF_8.name();
		String queryLang = getLanguageOfQuery(originalQuery);

		Translate.setClientId(langTranslationClientId);
		Translate.setClientSecret(LANG_TRANSLATION_SECRET_KEY);

		String finalQuery = originalQuery, translated_en = "", translated_ru = "", translated_de = "",
				translated_fr = "", translated_ar = "";

		if (queryLang.contains("en")) {
			CUSTOM_QF[0] = "tweet_text_en^4.0";
			CUSTOM_QF[1] = "tweet_text_exact_en^6.0";

			CUSTOM_QF[2] = "tweet_text_ru^2.0";
			CUSTOM_QF[3] = "tweet_text_exact_ru^3.0";

			CUSTOM_QF[4] = "tweet_text_de^2.0";
			CUSTOM_QF[5] = "tweet_text_exact_de^3.0";

			CUSTOM_QF[6] = "tweet_text_fr^2.0";
			CUSTOM_QF[7] = "tweet_text_exact_fr^3.0";

			CUSTOM_QF[8] = "tweet_text_ar^2.0";
			CUSTOM_QF[9] = "tweet_text_exact_ar^3.0";

			try {
				translated_ru = Translate.execute(originalQuery, Language.ENGLISH, Language.RUSSIAN);
				translated_de = Translate.execute(originalQuery, Language.ENGLISH, Language.GERMAN);
				translated_fr = Translate.execute(originalQuery, Language.ENGLISH, Language.FRENCH);
				translated_ar = Translate.execute(originalQuery, Language.ENGLISH, Language.ARABIC);
			} catch (Exception e) {
				e.printStackTrace();
			}

			finalQuery = "tweet_text_en:\"\"" + originalQuery + "\"\"" + " OR tweet_text_ru:\"\"" + translated_ru
					+ "\"\"" + " OR tweet_text_de:\"\"" + translated_de + "\"\"" + " OR tweet_text_fr:\"\""
					+ translated_fr + "\"\"" + " OR tweet_text_ar:\"\"" + translated_ar + "\"\"";

		} else if (queryLang.contains("ru")) {
			CUSTOM_QF[0] = "tweet_text_en^2.0";
			CUSTOM_QF[1] = "tweet_text_exact_en^3.0";

			CUSTOM_QF[2] = "tweet_text_ru^4.0";
			CUSTOM_QF[3] = "tweet_text_exact_ru^6.0";

			CUSTOM_QF[4] = "tweet_text_de^2.0";
			CUSTOM_QF[5] = "tweet_text_exact_de^3.0";

			CUSTOM_QF[6] = "tweet_text_fr^2.0";
			CUSTOM_QF[7] = "tweet_text_exact_fr^3.0";

			CUSTOM_QF[8] = "tweet_text_ar^2.0";
			CUSTOM_QF[9] = "tweet_text_exact_ar^3.0";

			try {
				translated_de = Translate.execute(originalQuery, Language.RUSSIAN, Language.GERMAN);
				translated_fr = Translate.execute(originalQuery, Language.RUSSIAN, Language.FRENCH);
				translated_ar = Translate.execute(originalQuery, Language.RUSSIAN, Language.ARABIC);
				translated_en = Translate.execute(originalQuery, Language.RUSSIAN, Language.ENGLISH);
			} catch (Exception e) {
				e.printStackTrace();
			}

			finalQuery = "tweet_text_ru:\"\"" + originalQuery + "\"\"" + " OR tweet_text_de:\"\"" + translated_de
					+ "\"\"" + " OR tweet_text_fr:\"\"" + translated_fr + "\"\"" + " OR tweet_text_ar:\"\""
					+ translated_ar + "\"\"" + " OR tweet_text_en:\"\"" + translated_en + "\"\"";

		} else if (queryLang.contains("de")) {
			CUSTOM_QF[0] = "tweet_text_en^2.0";
			CUSTOM_QF[1] = "tweet_text_exact_en^3.0";

			CUSTOM_QF[2] = "tweet_text_ru^2.0";
			CUSTOM_QF[3] = "tweet_text_exact_ru^3.0";

			CUSTOM_QF[4] = "tweet_text_de^4.0";
			CUSTOM_QF[5] = "tweet_text_exact_de^6.0";

			CUSTOM_QF[6] = "tweet_text_fr^2.0";
			CUSTOM_QF[7] = "tweet_text_exact_fr^3.0";

			CUSTOM_QF[8] = "tweet_text_ar^2.0";
			CUSTOM_QF[9] = "tweet_text_exact_ar^3.0";

			try {
				translated_fr = Translate.execute(originalQuery, Language.GERMAN, Language.FRENCH);
				translated_ar = Translate.execute(originalQuery, Language.GERMAN, Language.ARABIC);
				translated_en = Translate.execute(originalQuery, Language.GERMAN, Language.ENGLISH);
				translated_ru = Translate.execute(originalQuery, Language.GERMAN, Language.RUSSIAN);
			} catch (Exception e) {
				e.printStackTrace();
			}

			finalQuery = "tweet_text_de:\"\"" + originalQuery + "\"\"" + " OR tweet_text_fr:\"\"" + translated_fr
					+ "\"\"" + " OR tweet_text_ar:\"\"" + translated_ar + "\"\"" + " OR tweet_text_en:\"\""
					+ translated_en + "\"\"" + " OR tweet_text_ru:\"\"" + translated_ru + "\"\"";

		} else if (queryLang.contains("fr")) {
			CUSTOM_QF[0] = "tweet_text_en^2.0";
			CUSTOM_QF[1] = "tweet_text_exact_en^3.0";

			CUSTOM_QF[2] = "tweet_text_ru^2.0";
			CUSTOM_QF[3] = "tweet_text_exact_ru^3.0";

			CUSTOM_QF[4] = "tweet_text_de^2.0";
			CUSTOM_QF[5] = "tweet_text_exact_de^3.0";

			CUSTOM_QF[6] = "tweet_text_fr^4.0";
			CUSTOM_QF[7] = "tweet_text_exact_fr^6.0";

			CUSTOM_QF[8] = "tweet_text_ar^2.0";
			CUSTOM_QF[9] = "tweet_text_exact_ar^3.0";

			try {
				translated_ar = Translate.execute(originalQuery, Language.FRENCH, Language.ARABIC);
				translated_en = Translate.execute(originalQuery, Language.FRENCH, Language.ENGLISH);
				translated_ru = Translate.execute(originalQuery, Language.FRENCH, Language.RUSSIAN);
				translated_de = Translate.execute(originalQuery, Language.FRENCH, Language.GERMAN);
			} catch (Exception e) {
				e.printStackTrace();
			}

			finalQuery = "tweet_text_fr:\"\"" + originalQuery + "\"\"" + " OR tweet_text_ar:\"\"" + translated_ar
					+ "\"\"" + " OR tweet_text_en:\"\"" + translated_en + "\"\"" + " OR tweet_text_ru:\"\""
					+ translated_ru + "\"\"" + " OR tweet_text_de:\"\"" + translated_de + "\"\"";

		} else if (queryLang.contains("ar")) {
			CUSTOM_QF[0] = "tweet_text_en^2.0";
			CUSTOM_QF[1] = "tweet_text_exact_en^3.0";

			CUSTOM_QF[2] = "tweet_text_ru^2.0";
			CUSTOM_QF[3] = "tweet_text_exact_ru^3.0";

			CUSTOM_QF[4] = "tweet_text_de^2.0";
			CUSTOM_QF[5] = "tweet_text_exact_de^3.0";

			CUSTOM_QF[6] = "tweet_text_fr^2.0";
			CUSTOM_QF[7] = "tweet_text_exact_fr^3.0";

			CUSTOM_QF[8] = "tweet_text_ar^4.0";
			CUSTOM_QF[9] = "tweet_text_exact_ar^6.0";

			try {
				translated_en = Translate.execute(originalQuery, Language.ARABIC, Language.ENGLISH);
				translated_ru = Translate.execute(originalQuery, Language.ARABIC, Language.RUSSIAN);
				translated_de = Translate.execute(originalQuery, Language.ARABIC, Language.GERMAN);
				translated_fr = Translate.execute(originalQuery, Language.ARABIC, Language.FRENCH);
			} catch (Exception e) {
				e.printStackTrace();
			}

			finalQuery = "tweet_text_ar:\"\"" + originalQuery + "\"\"" + " OR tweet_text_en:\"\"" + translated_en
					+ "\"\"" + " OR tweet_text_ru:\"\"" + translated_ru + "\"\"" + " OR tweet_text_de:\"\""
					+ translated_de + "\"\"" + " OR tweet_text_fr:\"\"" + translated_fr + "\"\"";

		} else {
			CUSTOM_QF[0] = "tweet_text_en^2.0";
			CUSTOM_QF[1] = "tweet_text_exact_en^3.0";

			CUSTOM_QF[2] = "tweet_text_ru^2.0";
			CUSTOM_QF[3] = "tweet_text_exact_ru^3.0";

			CUSTOM_QF[4] = "tweet_text_de^2.0";
			CUSTOM_QF[5] = "tweet_text_exact_de^3.0";

			CUSTOM_QF[6] = "tweet_text_fr^2.0";
			CUSTOM_QF[7] = "tweet_text_exact_fr^3.0";

			CUSTOM_QF[8] = "tweet_text_ar^2.0";
			CUSTOM_QF[9] = "tweet_text_exact_ar^3.0";

			try {
				translated_en = Translate.execute(originalQuery, Language.ENGLISH);
				translated_ru = Translate.execute(originalQuery, Language.RUSSIAN);
				translated_de = Translate.execute(originalQuery, Language.GERMAN);
				translated_fr = Translate.execute(originalQuery, Language.FRENCH);
				translated_ar = Translate.execute(originalQuery, Language.ARABIC);
			} catch (Exception e) {
				e.printStackTrace();
			}

			finalQuery = "tweet_text_en:\"\"" + translated_en + "\"\"" + " OR tweet_text_ru:\"\"" + translated_ru
					+ "\"\"" + " OR tweet_text_de:\"\"" + translated_de + "\"\"" + " OR tweet_text_fr:\"\""
					+ translated_fr + "\"\"" + " OR tweet_text_ar:\"\"" + translated_ar + "\"\"";
		}

		if (originalQuery.contains("#")) {
			CUSTOM_QF[10] = "tweet_hashtags^5.0";
		} else {
			CUSTOM_QF[10] = "tweet_hashtags^1";
		}

		CUSTOM_QF[11] = "tweet_favorite_count^5.0";
		CUSTOM_QF[12] = "tweet_retweet_count^5.0";
		CUSTOM_QF[13] = "user_followers_count^5.0";
		CUSTOM_QF[14] = "user_listed_count^4.0";

		customParams.add(DisMaxParams.QF, CUSTOM_QF);
		customParams.add(DisMaxParams.PF, CUSTOM_QF);
		customParams.add(DisMaxParams.PF2, CUSTOM_QF);
		customParams.add(DisMaxParams.PF3, CUSTOM_QF);
		customParams.add(DisMaxParams.MM, "2<1 8<2");
		
		params = SolrParams.wrapAppended(params, customParams);
		return new ExtendedDismaxQParser(finalQuery, localParams, params, req);
	}

	/**
	 * @param queryText
	 * 
	 * @code The Language of query is tested by firing a HTTP post request to
	 *       http://ws.detectlanguage.com/0.2/detect.
	 * 
	 *       The output result is in JSON Format which is parsed to extract
	 *       language field.
	 */
	public String getLanguageOfQuery(String queryText) {
		String lang = "";
		queryText = queryText.replace(" ", "+");
		String response;

		try {
			String query = String.format("q=%s&key=%s", URLEncoder.encode(queryText, charSet),
					URLEncoder.encode(LANG_DETECTION_API_KEY, charSet));

			response = fetchHTTPData(langDetectionUrl, query);

			if (!response.equals(""))
				lang = parseJSONStringAndFindLanguage(response);
			else
				System.out.println("No response from Language detection server...");

		} catch (Exception ex) {
			System.out.println("Exception occured while detecting language...");
			ex.printStackTrace();
		}

		return lang;
	}

	public String parseJSONStringAndFindLanguage(String jSONString) throws JSONException {
		JSONObject jObj;
		String language = "";

		jObj = new JSONObject(jSONString);
		language = jObj.getJSONObject("data").getJSONArray("detections").getJSONObject(0).get("language").toString();

		return language;
	}

	public String fetchHTTPData(String URL, String query) throws IOException {
		String response = "";
		int responseCode = 0;

		HttpURLConnection httpConn = (HttpURLConnection) new URL(URL + "?" + query).openConnection();
		System.out.println("httpConn : " + httpConn);

		httpConn.setDoOutput(true);
		httpConn.setRequestProperty("Accept-Charset", charSet);
		httpConn.setRequestProperty("User-Agent", USER_AGENT);
		httpConn.setRequestProperty("Content-type", "text/json; charset=utf-8");

		responseCode = httpConn.getResponseCode();

		if (responseCode == 200) {
			BufferedReader in = new BufferedReader(new InputStreamReader(httpConn.getInputStream()));
			String inputLine;
			StringBuffer responseBuffer = new StringBuffer();

			while ((inputLine = in.readLine()) != null) {
				responseBuffer.append(inputLine);
			}

			in.close();
			response = responseBuffer.toString();
		}

		return response;
	}

}