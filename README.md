# Dodgeproxy

This is a very dodgy quickly whipped together script for a proxy on Apache.

For those times when mod_rewrite and mod_proxy are just not enough, but you
can't or don't want to deploy a full reverse proxy, this is for you!


## What's implemented:

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

# Why dodgeproxy?
Usually for a reverse proxy, I would just use something like nginx or varnish,
however, when migrating websites from shared hosting over to dedicated servers,
sometimes you will not have access to install these on existing servers.

This was quickly written so sites could be migrated from existing hosts
to new hosting servers without any downtime, as long as Apache was installed
with htaccess support. (Think, cPanel shared hosting accounts)
