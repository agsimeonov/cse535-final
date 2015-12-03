
import java.io.File;
import java.io.FileNotFoundException;
import java.io.FileReader;
import java.io.FileWriter;
import java.io.IOException;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Date;
import java.util.TimeZone;
import java.util.regex.Matcher;
import java.util.regex.Pattern;

import org.json.simple.JSONArray;
import org.json.simple.JSONObject;
import org.json.simple.parser.JSONParser;
import org.json.simple.parser.ParseException;

import com.google.gson.stream.JsonWriter;

public class JsonBeauty {

	private static long tweet_id, user_id, tweet_favorite_count, tweet_retweet_count, user_friends_count,
			user_followers_count, user_statuses_count, user_favourites_count, user_listed_count;

	private static String tweet_created_at, tweet_lang, tweet_text_en = "", tweet_text_ru = "", tweet_text_de = "",
			tweet_text_fr = "", tweet_text_ar = "", user_created_at, user_name, user_screen_name, user_location,
			user_location_geo, user_location_tweet, user_lang, user_description;

	private static boolean user_verified;

	private static int tweetCounter = 0;

	// Tweet collection
	private static String rawDataPath = "/home/raman/Raman_Work/Workspace/JsonBeautifier/data/raw/";
	private static String extractedDataPath = "/home/raman/Raman_Work/Workspace/JsonBeautifier/data/extracted/";
	private static String geocoderFilePath = "/home/raman/Raman_Work/Workspace/JsonBeautifier/data/geocoder.json";

	private static FileReader geoReader;
	private static JSONParser geoJsonParser;
	private static JSONObject geoObject;
	// private static JsonWriter tweetFile;

	private static String[] languages = { "en", "de", "ru", "fr", "ar" };
	private static String[] dates = { "2015-11-20", "2015-11-21", "2015-11-22", "2015-11-23", "2015-11-24",
			"2015-11-25", "2015-11-26" };

	private static void processTweets(String fileName, String mDate, String mLang, JsonWriter tweetFile) {
		try {
			// read the json file
			FileReader reader = new FileReader(rawDataPath + mDate + "/" + mLang + "/" + fileName);
			JSONParser jsonParser = new JSONParser();

			JSONObject entityObject, userObject;
			JSONArray urlArray, mediaArray, hashtagArray, usersMentionedArray;
			JSONObject tweetObject = (JSONObject) jsonParser.parse(reader);


			// tweet specific information
			tweet_id = (long) tweetObject.get("id");
			tweet_created_at = (String) tweetObject.get("created_at");
			tweet_lang = (String) tweetObject.get("lang");

			if (tweet_lang.equals("en")) {
				tweet_text_en = (String) tweetObject.get("text");
				tweet_text_ru = "";
				tweet_text_de = "";
				tweet_text_fr = "";
				tweet_text_ar = "";
			} else if (tweet_lang.equals("ru")) {
				tweet_text_ru = (String) tweetObject.get("text");
				tweet_text_de = "";
				tweet_text_fr = "";
				tweet_text_ar = "";
				tweet_text_en = "";
			} else if (tweet_lang.equals("de")) {
				tweet_text_de = (String) tweetObject.get("text");
				tweet_text_fr = "";
				tweet_text_ar = "";
				tweet_text_en = "";
				tweet_text_ru = "";
			} else if (tweet_lang.equals("fr")) {
				tweet_text_fr = (String) tweetObject.get("text");
				tweet_text_ar = "";
				tweet_text_en = "";
				tweet_text_ru = "";
				tweet_text_de = "";
			} else if (tweet_lang.equals("ar")) {
				tweet_text_ar = (String) tweetObject.get("text");
				tweet_text_en = "";
				tweet_text_ru = "";
				tweet_text_de = "";
				tweet_text_fr = "";
			}

			tweet_favorite_count = (long) tweetObject.get("favorite_count");
			tweet_retweet_count = (long) tweetObject.get("retweet_count");

			// User specific information
			userObject = (JSONObject) tweetObject.get("user");
			user_id = (long) userObject.get("id");
			user_created_at = (String) userObject.get("created_at");
			user_name = (String) userObject.get("name");
			user_screen_name = (String) userObject.get("screen_name");
			user_description = (String) userObject.get("description");
			user_verified = (boolean) userObject.get("verified");
			user_friends_count = (long) userObject.get("friends_count");
			user_followers_count = (long) userObject.get("followers_count");
			user_statuses_count = (long) userObject.get("statuses_count");
			user_favourites_count = (long) userObject.get("favourites_count");
			user_listed_count = (long) userObject.get("listed_count");
			user_lang = (String) userObject.get("lang");
			// user_location_tweet = (String) userObject.get("location");
			// user_location_geo = (String)
			// geoObject.get(String.valueOf(tweet_id));
			user_location = (String) geoObject.get(String.valueOf(tweet_id));

			// if (null == user_location)
			// return;

			tweetFile.beginObject();
			tweetFile.name("tweet_id").value(tweet_id);
			tweetFile.name("tweet_created_at").value(toUtcDate(tweet_created_at));
			tweetFile.name("tweet_lang").value(tweet_lang);
			tweetFile.name("tweet_text_en").value(tweet_text_en);
			tweetFile.name("tweet_text_ru").value(tweet_text_ru);
			tweetFile.name("tweet_text_de").value(tweet_text_de);
			tweetFile.name("tweet_text_fr").value(tweet_text_fr);
			tweetFile.name("tweet_text_ar").value(tweet_text_ar);
			tweetFile.name("tweet_favorite_count").value(tweet_favorite_count);
			tweetFile.name("tweet_retweet_count").value(tweet_retweet_count);

			entityObject = (JSONObject) tweetObject.get("entities");

			tweetFile.name("tweet_urls");
			tweetFile.beginArray();

			mediaArray = (JSONArray) entityObject.get("media");
			if (null != mediaArray && mediaArray.size() > 0) {
				int count = mediaArray.size();
				int counter = 0;

				while (counter < count) {
					tweetFile.value((String) ((JSONObject) mediaArray.get(counter)).get("expanded_url"));
					++counter;
				}
			}

			urlArray = (JSONArray) entityObject.get("urls");
			if (null != urlArray && urlArray.size() > 0) {
				int count = urlArray.size();
				int counter = 0;

				while (counter < count) {
					tweetFile.value((String) ((JSONObject) urlArray.get(counter)).get("expanded_url"));
					++counter;
				}
			}

			tweetFile.endArray();

			tweetFile.name("tweet_hashtags");
			tweetFile.beginArray();

			hashtagArray = (JSONArray) entityObject.get("hashtags");
			if (null != hashtagArray && hashtagArray.size() > 0) {
				int count = hashtagArray.size();
				int counter = 0;

				while (counter < count) {
					tweetFile.value((String) ((JSONObject) hashtagArray.get(counter)).get("text"));
					++counter;
				}
			}

			tweetFile.endArray();

			tweetFile.name("tweet_users_mentioned");
			tweetFile.beginArray();

			usersMentionedArray = (JSONArray) entityObject.get("user_mentions");
			if (null != usersMentionedArray && usersMentionedArray.size() > 0) {
				int count = usersMentionedArray.size();
				int counter = 0;

				while (counter < count) {
					tweetFile.value((String) ((JSONObject) usersMentionedArray.get(counter)).get("name"));
					++counter;
				}
			}
			tweetFile.endArray();

			tweetFile.name("user_id").value(user_id);
			tweetFile.name("user_created_at").value(toUtcDate(user_created_at));
			tweetFile.name("user_name").value(user_name);
			tweetFile.name("user_screen_name").value(user_screen_name);
			tweetFile.name("user_lang").value(user_lang);
			tweetFile.name("user_description").value(user_description);
			tweetFile.name("user_verified").value(user_verified);
			tweetFile.name("user_friends_count").value(user_friends_count);
			tweetFile.name("user_followers_count").value(user_followers_count);
			tweetFile.name("user_statuses_count").value(user_statuses_count);
			tweetFile.name("user_favourites_count").value(user_favourites_count);
			tweetFile.name("user_listed_count").value(user_listed_count);
			tweetFile.name("user_location").value(user_location);

			tweetFile.endObject();

			++tweetCounter;
			System.out.println("\n " + tweetCounter + " tweets copied to File...");

		} catch (FileNotFoundException ex) {
			ex.printStackTrace();
			System.out.println("\n Exception when copying tweet number " + tweetCounter + " to File");
		} catch (IOException ex) {
			ex.printStackTrace();
			System.out.println("\n Exception when copying tweet number " + tweetCounter + " to File");
		} catch (ParseException ex) {
			ex.printStackTrace();
			System.out.println("\n Exception when copying tweet number " + tweetCounter + " to File");
		} catch (NullPointerException ex) {
			ex.printStackTrace();
			System.out.println("\n Exception when copying tweet number " + tweetCounter + " to File");
		} catch (Exception ex) {
			ex.printStackTrace();
			System.out.println("\n Generic Exception when copying tweet number " + tweetCounter + " to File");
		}

	}

	public static String toUtcDate(String sourceDate) {
		String lv_dateFormateInUTC = ""; // Will hold the final converted date

		Date tweetDate = new Date(sourceDate);
		String ISO_FORMAT = "yyyy-MM-dd'T'HH:mm:ss.SSS zzz";
		SimpleDateFormat lv_formatter = new SimpleDateFormat(ISO_FORMAT);
		lv_formatter.setTimeZone(TimeZone.getTimeZone("UTC"));
		lv_dateFormateInUTC = lv_formatter.format(tweetDate);

		return lv_dateFormateInUTC.replace(" UTC", "Z");
	}

	public static ArrayList<String> extractTags(String tweetText) {
		// For ASCII lang
		// Pattern MY_PATTERN = Pattern.compile("#(\\w+|\\W+)");

		// for UTF 8 lang
		Pattern MY_PATTERN = Pattern.compile("(?:^|\\s|[\\p{Punct}&&[^/]])(#[\\p{L}0-9-_]+)");
		Matcher mat = MY_PATTERN.matcher(tweetText);
		ArrayList<String> tags = new ArrayList<String>();
		while (mat.find()) {
			tags.add("\"" + mat.group(1).replace("#", "") + "\"");
		}

		return tags;
	}

	public static ArrayList<String> extractAllUrls(String tweetText) {
		ArrayList<String> containedUrls = new ArrayList<String>();
		String urlRegex = "((https?|ftp|gopher|telnet|file):((//)|(\\\\))+[\\w\\d:#@%/;$()~_?\\+-=\\\\\\.&]*)";
		Pattern pattern = Pattern.compile(urlRegex, Pattern.CASE_INSENSITIVE);
		Matcher urlMatcher = pattern.matcher(tweetText);

		while (urlMatcher.find()) {
			containedUrls.add("\"" + tweetText.substring(urlMatcher.start(0), urlMatcher.end(0)) + "\"");
		}

		return containedUrls;
	}

	public static void listFilesForFolder(final File mFolder, String mDate, String mLang, JsonWriter tweetFile) {
		for (final File fileEntry : mFolder.listFiles()) {
			if (fileEntry.isDirectory()) {
				listFilesForFolder(fileEntry, mDate, mLang, tweetFile);
			} else {
				processTweets(fileEntry.getName(), mDate, mLang, tweetFile);
			}
		}
	}

	private static void extractData() throws IOException {
		for (String date : dates) {
			for (String lang : languages) {
				final File folder = new File(rawDataPath + date + "/" + lang + "/");
				JsonWriter tweetFile = null;
				try {
					File tweetDataFile = new File(extractedDataPath + "tweet_" + lang + "_" + date + ".json");

					if (!tweetDataFile.exists()) {
						tweetDataFile.createNewFile();
					}

					tweetFile = new JsonWriter(new FileWriter(tweetDataFile));
					tweetFile.beginArray();

					listFilesForFolder(folder, date, lang, tweetFile);

				} catch (IOException e) {
					e.printStackTrace();
				} finally {
					if (null != tweetFile) {
						tweetFile.endArray();
						tweetFile.close();
					}
				}
			}
		}
	}

	public static void main(String[] args) {
		try {
			geoReader = new FileReader(geocoderFilePath);
			geoJsonParser = new JSONParser();
			geoObject = (JSONObject) geoJsonParser.parse(geoReader);

			extractData();
		} catch (IOException e) {
			e.printStackTrace();
		} catch (ParseException e) {
			e.printStackTrace();
		}
	}

}
