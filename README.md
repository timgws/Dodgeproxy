#Dodgeproxy.

This is a very dodgy quickly whipped together script for a proxy on Apache.

For those times when mod_rewrite and mod_proxy are just not enough, but you
can't or don't want to deploy a full reverse proxy, this is for you!


##What's implemented:

* Reverse proxy with:
 * etag's for HTTP caching!
 * caching

Very dumb proxy. You will have to clear out the cache folder if you want
to see new/updated content.

Great for server migrations, simply place this on the old server while
the DNS propagates.

## Clearing the cache
I have put a rather crude rm -rfv inside `clear.php`. You might want
to remove this on a production reployment & protect it with a htpasswd.
