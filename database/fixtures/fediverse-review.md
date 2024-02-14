For the last 13 months, I have been operating a Mastodon (and later, glitch-soc) server. I’ve learned a lot in that time about both Mastodon (the software) and the fediverse as a whole.

There is a lot of Nuance involved, so I wanted to write everything I know down. This post will move seamlessly between social & technical matters because they each shape the other.

Feel free to skip sections or ctrl+F to find bits you care about. Here’s a table of contents for easier navigation:

[TOC]

## What is the Fediverse?

The Fediverse is a bunch of users & servers running software that interoperates via the ActivityPub protocol. If you sign up on `mastodon.social`, you can like posts on `pixelfed.org` despite them being run by different people and using entirely different software.

Anybody is allowed to start up a server. Nearly all other servers will be happy to federate: if you want to follow somebody, you put their username into your server and it’ll Just Work™ without needing any kind of federation agreement. Only a small number of servers seem to have a “deny by default” policy for federating with new servers.

Since there are thousands of servers and thousands of admins, the fediverse _as a whole_ is resilient to outages or some CEO deciding to charge $42,000/month for API access. Individual servers are vulnerable to DDoS, hardware failure, or admins getting bored and moving on.

The ActivityPub protocol is meant to work for any kind of social media app. Mastodon, Hometown, glitch-soc, Pleroma, Akkoma, Misskey, and Calckey are some of the popular Twitter-esque apps. But there’s also Pixelfed for sharing photos, PeerTube for sharing videos, and OwnCast for livestreams.

Last month, the _threadiverse_ also emerged as a word. This refers to Lemmy & kbin, both Reddit-esque apps for building communities and having long discussion threads.

I’ve run three pieces of software: vanilla Mastodon, `glitch-soc`, and kbin. `glitch-soc` is a fork of Mastodon maintained by ClearlyClaire, a core member of the vanilla Mastodon dev team. It’s very similar, but with a few extra features: markdown formatting, longer post limits, and a few UI tweaks.

## Picking a Server

If somebody decides to join the Fediverse, they need to decide on a server to join. If you have friends on a server, maybe they can give you an invite, and then it’s not a hard choice. But if somebody is starting from zero, it’s a bit harder.

There is a directory of servers on the Mastodon marketing site. This is probably where people will go if they’re exiting Twitter.

On most days, this might be fine. In days when Shit Is Going Down with Twitter, some (or all) of these servers may close registrations because they’re getting too overloaded to handle the traffic.

The directory creates problems too: the first couple servers listed tend to be where all the new users sign up. Moderating a server with 367k users is challenging. The `mastodon.social` moderation team has an unknown number of paid professionals, but they can’t be everywhere at once.

It is possible to move your account to another server, but this migration process has downsides. Migrating is not a substitute for picking a good server.

## Moderation in the Fediverse

There is no overall governing body for the Fediverse. There are no common rules and no United Nations. Every server has its own individual rules and moderation team.

If somebody is sending you nasty messages, you can report the post. The exact process differs depending on the software, but it should ultimately report them to _their server’s_ admin. Their post may not violate the rules on that server, resulting in no action. Their abusive posts can continue.

There are two main options for your server’s moderation team to protect you.

Banning that specific user from interacting with your server is the first one. They can still continue abusing other servers, or sign up for a new account to evade the ban.

The other is a nuclear option: defederating their server. In Mastodon, there are a few flavours: the server can be silenced to stop its posts from showing up in your federated feed, but still allowing you to follow people there and have their posts show in your home feed.

The other is suspending the server. This prevents any interaction at all; users and posts from that server can’t be loaded anymore. Suspension breaks any following/follower connections between users on those servers. Suspension is an extreme sanction and cannot be used lightly.

## Fediverse Safety

Prominent black folks from Twitter have joined Mastodon and been greeted with an immediate deluge of hate and death threats. They’ve posted about their experience.

There are probably a very large number number of not-white people who _aren’t_ famous Twitter users that have experienced the same and just deleted their account without looking back.

Preventing this from happening is impossible. It is a systemic failure of how the Fediverse works right now. It’s a severe failure.

The laissez faire federation model, where _any_ server can send messages to you, means that a bad actor can spin up as many servers as they can afford domain names. When that happens:

1.  They are their own admin, so reporting them will not help.
2.  Your own admin team cannot defederate their server if they’ve got three dozen more to harass you from.
3.  Your own admin team can spot & delete malicious public posts, but DMs are _generally_ opaque to them. You have to report it before they can see a hateful DM. And you _don’t_ want to see it.

This has been a common criticism of the Fediverse. [Here’s a post from Mekka](https://mastodon.cloud/@mekkaokereke@hachyderm.io/110273797518480937). It’s not hard to find more.

There is not a lot of will to switch from an open federation model to allow-list-only. That would help the situation somewhat, since it restricts where hate can come from, but it also means new servers face an up-hill battle to participate in the Fediverse.

Pixelfed has introduced a new federation model that might improve this situation if more software adopts it, called hybrid mode. The first time your server interacts with another, your moderation team is notified and they must review the server _before_ any hateful content is delivered to users.

Even with hybrid mode, a huge open-registration server like `mastodon.social` is still an avenue of attack. Your server will probably approve it since there are so many people there. Their moderation team can try their hardest to ban abusive users, but at that scale, they can only react.

I can’t find the post, but one of the ActivityPub spec authors, Christine Lemmer-Webber, wrote up a concept for a systemic solution. From what I recall, it was based on a web of trust: you can only interact with me if you’ve been authorized to do so, and I can give my friends the authority to let you talk to me. You can revoke a whole “tree” of trust, so if somebody has exposed you to a bad community, you can prune the whole thing without much fuss.

As far as I know, that concept is not being implemented by any Fediverse software right now.

## Spam

Oddly enough, there is very little spam on the Fediverse. I do not know why this is the case, but I’ve only ever had to deal with three spammers sending cryptocurrency garbage to my users.

This is odd to me because the Fediverse is similar to email in the early ’00s: vulnerable to spam with few tools to fight back. It should be a spammer’s paradise.

The open federation model means it’s very easy to spin up servers that churn out spam DMs or sign up a ton of accounts on an open-registration server.

Mastodon servers have a standard UI with feeds showing tons of active users that can be scraped.

In Mastodon, there is no hook for bayesian filtering for incoming posts or anything of that nature. We will eventually need tools like this. If Pixelfed’s hybrid federation model becomes popular, it would shut the door on a lot of potential for spamming.

But, for whatever reason, this is not _currently_ a problem.

## The Politics of Federation

There are thousands of servers run by thousands of people. This results in _frequent_ disagreements over moderation & content policies. Defederation occurs when the disagreement cannot be resolved.

In many cases, this is fine. There are some known servers that exist just to troll and harass people. If nobody on your server is friends with that crowd, the defederation is invisible, hurts nobody on your server, and protects you from abuse.

But remember: a full on “suspend” defederation breaks follower/following connections. If two admins of major servers disagree with each other and defederate, _all_ of those social connections are broken. At least in Mastodon, removing the “suspend” does not restore the connections.

This is _extremely_ disruptive to users. In Mastodon, it doesn’t even tell you that some of your friends have been lost to the void. When the defederation happens, _poof_, they’re gone.

As a normal user, you don’t have any recourse. You can appeal to the admin, but even if they restore federation with that server, you need to go re-follow people and hope they follow you back (but remember: they don’t even know they stopped following!).

For example, last month, the admin of `mastodon.art` and `mstdn.social` got into a disagreement. The `mstdn.social` admin decided in a fit of pique to defederate the art server. All of those followers were lost to the void. They changed their mind on defederating a few hours later, but the damage was done.

Defederation is confusing too. If you’re searching for a user or a post on a server that has been defederated, it simply won’t come up. You can open that user’s profile on their home server … but when you try to pull them up on your server to follow them, you get no results or explanation.

One of the upsides that people talk about with the Fediverse is controlling your own data, or your own social media destiny. If you’re a user on somebody else’s server, this simply isn’t true: you’re part of their personal fief.

## Moving to a Different Server

In Mastodon, it’s possible to move to a new server. You can take your followers with you and the old account will prominently show your new account.

The downside is that all of your posts stay behind. You can export your posts, but there’s no way to re-import them on the new server. I’m not even sure if there are any good tools to view the exported posts. There is an open issue for migrating posts, but I don’t think any work has been done there?

The migration process requires your old server to be up. If you want to migrate because your server suddenly shut down, you cannot. Your only option is to make a new account and start from scratch.

When you set up the migration, it may take a little while for all of your followers to migrate. The new server has to send a follow request to every single person and wait for it to be acknowledged; if the server is busy, that’s not its top priority. Private accounts still have to approve the follow request \[I think?\].

And if you’re moving because your admin defederated a server with people you care about, those connections are already broken and they can’t be migrated.

## Post Caching

Mastodon will _aggressively_ cache everything it can locally. Media expires because you can’t have an image folder grow forever, but it will remember posts forever. I would expect most Fediverse software to behave the same.

This is necessary for performance and searching. It’s inefficient for your server to re-download a post from another server every time it shows up in a user’s feed. If you have 5,000 users online and a post pops into the Federated Feed, you don’t want to send 5,000 requests to that other server all at once — it would probably crash!

The implication here is: once you post something, there are no take-backs. Deleting or editing your post sends a new event to servers, and they _should_ respect it, but they don’t _have to_. Some software might not understand edits or deletes. Some may disregard that on purpose, or show every version.

One of the social features of Mastodon is that the search sucks on purpose. This is done to prevent weird nerds from camping the search results for “Elon Musk”.

![](https://godlessinternets.files.wordpress.com/2023/07/d5jdx73mbi091.jpg)

But server admins don’t have to use the Mastodon search field though. All of that raw post data is available to them in the database for whatever purpose they want.

Another implication: copies of posts from long-dead servers are still available somewhere out there in the Fediverse. Their media may be lost, but the text lives on.

## Setting Up a Server

Mastodon (and its forks, `glitch-soc` & Hometown) are fairly mature pieces of software. They are complex though. Here’s a diagram of `mastodon.yshi.org` — it’s a little outdated but it illustrates the point of _this shit’s complicated_:

![Mastodon service architecture diagram](https://godlessinternets.files.wordpress.com/2023/07/mastodon-service-design.png?w=782)

Mastodon has reasonable documentation, but this is _not_ a thing for the faint of heart to run.

Most of the software I’ve looked at has a similar architecture: the main web/API app, some “worker” processes in the background for pushing & pulling ActivityPub activities, and a websocket server for push notifications.

I always use an S3-compatible bucket for storing images/avatars/link preview cards. Disk on the object store is generally way cheaper than on a VM. With our vanity domain on the bucket, it’s very easy to move media storage between providers.

My `glitch-soc` server is bleeding-edge Mastodon. It tracks ahead of the tagged released. This is Slightly Scary, but it’s worth it for stuff like “thread mode” so the post box auto-replies in your thread.

If you want to run your own personal server, you can install Mastodon, or you could check out something like Akkoma. I think that one is meant for single-user or very-small-sizes communities.

It’s also worth noting: Pleroma has a bad reputation. There is nothing wrong with the _software_, but I think a lot of bad actors use (used to use?) it for sending nasty messages. I still see an occasional complaint about Pleroma discrimination; I guess some admins defederate Pleroma servers at the drop of a hat.

## Multiple Fedi Services on One Domain

All of the Fediverse software should support the Webfinger protocol, which lets you serve a special URL on your root domain (e.g. `yshi.org`) and tells you a bunch of URLs for a Mastodon user.

This is how I can be `@owls@yshi.org` but the Mastodon server is running on `mastodon.yshi.org` — somebody Webfingers me and it tells them “oh, yeah, you need to go to the subdomain for that”.

There’s a small problem with this when you want to run 2+ services on the same domain: Webfinger doesn’t understand the concept of services. I can serve one set of URLs for `@owls@yshi.org`, and no more.

It’s not a huge deal that my kbin user is `@owls@community.yshi.or`g, but it’s something to be aware of.

## Operations Burden

Mastodon works well. I’ve only ever had Elasticsearch crash when it’s tried to use all the RAM. I’ve had certbot spazz out a few times and fail to renew certs, but I have UptimeRobot monitoring Mastodon and the media bucket.

The biggest burden has been doing upgrades, and that’s easy: do whatever the Mastodon release notes say to do. The `glitch-soc` UI takes forever to build, but nothing in the upgrade process has been hard or risky.

That said: there are _a lot_ of moving parts. I happen to be capable with Rails, JS, and sysadmin stuff, so nothing Mastodon has thrown at me has been beyond my knowledge. If you don’t have those skill sets, I’m not sure how you’d handle something breaking. And eventually, something _will_ break.

## Other Hosting Options

Some providers have a “one click install” for Mastodon. For example, Linode has that. You still need to manage the server and do upgrades yourself, so it’s not as helpful as you’d expect.

I know of two hosting companies for managed Mastodons. I have not personally used either. One of my friends uses `masto.host` for a single-user server and he’s been happy with it.

*   [https://masto.host/](https://masto.host/)
*   [https://fedi.monster/](https://fedi.monster/)

## Initial Defederation List

There are some known-bad-actors on the Fediverse (and also pawoo). Most servers publish the servers they’ve defederated and a reason, so you can go look at `mastodon.social` and see what they’ve banned as a starting point.

I do not publish my list to avoid attracting attention from the servers I have defederated. If you want it and I know you, I’d be willing to share on request.

In a recent Mastodon releases, they added an import/export feature for defederated servers, so it’s easy to pass a list around between admins these days.

Since my server is very small, I don’t go out of my way to find servers to block. If I see something in the `#fediblock` hashtag from somebody I trust and it’s a good reason, I’ll block the server in question. But otherwise, I just wait for the abuse to come and react to it.

## Getting Posts to your Server

Mastodon (and probably everything else) has a “Federated Feed” showing every post your server knows about. This is a good tool for discovering Good Posters to follow.

But on a brand-new Mastodon server, this will be empty. It doesn’t know about any posts yet. It won’t until other servers start sending you posts.

The way the federation works is posts are sent to your server on an as-needed basis. If I post on my own server, that will not automagically be sent to your new server. There is no central registry of “every server to send to” — it’s _mostly_ based on followers.

If you follow me and I post, then my server knows “okay, $YOU need the post”, and it’ll send it over. Everybody on your server can see it in the Federated Feed, so your friend might think it’s a good post and start following me too.

This is true for searching hashtags, users, etc — your server can only search _stuff it knows about_, an exact username (`@owls@yshi.org`), or an exact post URL.

There are some complexities involved in federating replies, handling boosts, or manually loading a post on your server by searching its URL. But in general, the easiest way to get more Stuff on your server is to follow more people.

For the first nine months or so, I would visit other Mastodon servers to browse their local feeds or search hashtags. That’s usually available to non-users, so you don’t need to make a bunch of accounts, just go poke around.

Whenever I found somebody or something cool, I’d punch their username in to my own server and start following them.

## Relays for Getting More Posts

An alternative to my strategy of “hunting down cool people to follow” is using a relay service. Relays capture all the posts from one (or more) servers and re-broadcast them to yours. This populates your Federated Feed, which helps you discover cool people.

I’ve been using [the FediBuzz relay](https://relay.fedi.buzz/) for specific hashtags. In Mastodon, you can add it under Administration -> Relays. FediBuzz will auto-approve you after a few seconds, so if it says “Pending”, just refresh and it should indicate it’s all set.

I follow most of the hashtags I’ve added FediBuzz relays for.

## Server Resource Consumption

To give you an idea of how much server you need for `glitch-soc`: I have about ten users, all of whom are active. The server launched in May 2022.

The database is currently 1.82 GB, and the media storage is at 182 GB.

A lot of the media is cached images/avatars/link preview cards that gets cleaned out and replaced by media for new posts, so that rolls over a bit. It scales by two factors:

1.  The size of media posted by local users. This is permanently stored, unless they delete their post.
2.  The size of media for posts, avatars, and link preview cards from other servers we’ve seen in the last 2 weeks. Mastodon caches this for a configurable period of time.

Most of the storage is #2, but we don’t post a ton of images and videos.

The VM has 2 vCPUs and 4 GB of RAM. It fits Postgres, the default Sidekiq setup, and Elasticsearch just fine. I do stop Elasticsearch during upgrades because building the frontend is intensive.

I use Linode for hosting. The whole thing costs me about $34/m. $5 of that is for the object store, which is shared between a couple things I run.

## Content Warning Culture

Mastodon and probably all other Fediverse software supports hiding your post content behind a content warning (CW). There is a culture of hiding a lot of things behind CWs, to the point some users find burdensome.

For example: someone coming from Instagram will find it normal to post selfies with wild abandon. Some people have issues with eye contact and may find that post uncomfortable, so the CW ‘eye contact’ might be used.

There are no hard-and-fast rules on what to CW. Your server may have some, which you should do your best to respect. Otherwise, you just need to know your audience. Be open to feedback from people.

There is a “post police” culture on the Fediverse. If somebody is aggressive, rude, or demanding about your use of CWs: block and/or report.

I’ve never asked somebody to CW stuff. I’m not the Post Police, and it seems rude to do that.

There’s a non-obvious contention I want to note: some CWs may have a plus, minus, or tilde. For example, `mh-` means `Mental Health (Negative)`, so the post is probably a bummer. The plus is for something good, and the tilde is neutral.

CWs might also be used to collapse a long post so they don’t have to scroll through it, or as a general subject line. Mastodon displays CW’d posts with just the CW until the user clicks through.

## Alt Text

When posting an image or a video, you should include alt text. People who use assistive technologies like a screen reader rely on the alt text since they might not be able to see the image.

This isn’t specific to the Fediverse; this is just a thing every website has to deal with to ensure everyone is included in the conversation.

For screenshots of text, it’s easy enough to copy-paste the text in. Mastodon offers an OCR feature to try and extract text from images.

But for textless images, writing good alt text is a bit of an art. You need to convey the point of the image to somebody who can’t see it, so they can understand the post. But at the same time, over-description can be as bad as having no alt text at all.

This is another area where you may encounter the “post police” culture: some people might get mad at alt-text-less images. Again, if they’re being abusive, block & report.

If you choose not to include alt text, some people will not boost your post. Other folks might reply with alt text. If you want to, you can edit your post to update the image with that text.

I try to include alt text when I’m at my desk. I occasionally liveblog tasting menus on Mastodon, and writing alt text from my phone while I’m trying to eat isn’t viable. My advice is to make an effort when you can.

## Flexible Muting Feature

In Mastodon, you can mute somebody for a set period of time (or forever, but that’s less useful to me).

I think it’s a _fantastic_ feature. When there’s somebody I really like following, but they’re posting a bunch about baseball or something else I don’t care about, I can mute them for a few hours to get the sportsball off my feed.

## TweetDeck UI

There are two different UIs in the Mastodon web app: the normal Twitter-esque UI, and the advanced TweetDeck-esque UI.

You can enable it from Preferences -> Appearance -> Advanced Web Interface.

I’ve got my home feed, federated feed, and a couple tag feeds. I guess I should have tried TweetDeck out back in the day, because I really like this.

I don’t think it lets you monitor multiple accounts. I’ve never used TweetDeck but that sounds like it was an important feature.

## Mastodon API vs ActivityPub

The ActivityPub spec requires certain API endpoints for client-to-server and server-to-server interactions. These cover everything needed for the basics of social media: posting, liking, following, etc.

But there’s more stuff that your client may need to do beyond the basic ActivityPub actions. For example, searching for a hashtag is something that the Mastodon Client API handles rather than the ActivityPub API.

Most of the Twitter-esque Fediverse software implements the Mastodon API, and most desktop or mobile clients will expect the Mastodon Client API.

So you can use the official iOS Mastodon app with an Akkoma server. You might not have access to all the features of Akkoma, but whatever overlaps with Mastodon should work. So, if you’re running something other than Mastodon, all of its apps _should_ still give you a good experience.

## Mobile Apps for iOS

I personally use Metatext on iOS. This app is not maintained anymore and doesn’t support editing posts. It occasionally spazzes out and crashes. It’s free and it’s what I’ve used for a while. I’m holding out for a fork called [Feditext](https://github.com/feditext/feditext) to be released.

When I tried the official iOS Mastodon app out, I didn’t think it worked well. It was hard to refresh my feed, I think? But I think they’ve overhauled it completely since then, so it might be better now.

I see people recommending tooot a lot, which is free. I have not tried it.

There’s also Ivory, which has a small monthly subscription fee. It’s from the Tweetbot developers. Ivory is still a work in progress and doesn’t support custom emoji, so that’s really a non-starter for me. I’ll probably check it out when they finish the items on their roadmap.

## Like vs Boost

Some people on the Fediverse say that you should boost posts instead of liking them, since there’s no algorithm to use the “likes” to identify trending posts.

Liking a post just sends a notification to the author that you liked the post. The “Like” counter doesn’t federate well, so it’s a poor indicator of popularity. It will be accurate _only_ on the poster’s home server, since all Likes have to be sent there. They are not forwarded to other servers, resulting in the inaccuracy.

Boosting a post re-posts it to your followers, which can expose the post to new servers. More people may see it in the Federated Feed, decide the author is a cool poster, and follow them. This is the best way of increasing somebody’s reach on the Fediverse.

I mostly like posts and boost the funniest jokes or art I really like. There’s not really a correct thing to do.

## Federating Replies

One big caveat to federation is that replies can be fragmented when viewing a post from another server. I think it’s possible for replies-to-replies to get fragmented too, if the original author isn’t being tagged?

Basically: a server only sends a post to servers that need to see it.

Imagine that we have Alice, Bob, and Cody on three different servers. Alice makes a post; Cody replies. Bob follows Alice, but not Cody. So Bob’s server gets Alice’s post delivered, but Cody’s server has no reason to send his reply to Bob’s server.

If you open the post on its original server, you can see Cody’s response. But if you view it from your server, no replies show up.

This can be confusing.

## Users with No Posts

If you try following your friend on a brand-new Mastodon server, their profile will come up, but they probably won’t have any posts.

This is again because Mastodon can only show posts it has been told about. If you follow them, new posts they make will be sent to your server, but everything before following them will be missing.

Mastodon does not try to load their post history posts when you follow someone. You can search an exact post URL to load it so you can like/boost, but there’s no easy way to grab their entire history.

Other people on the server boosting or following them may have caused the posts to get sent over, in which case there will be some post history to show. It may be incomplete.

## Gargron & Mastodon gGmbH

Mastodon is developed by a non-profit company. They make their money from sponsorships, Patreon money, and grants. Gargron is in charge of the whole operation and serves as the primary developer of Mastodon. Mastodon gGmbH is the best-funded and most-influential project in the Fediverse.

Besides developing the Mastodon server, they also have official iOS & Android apps. They operate the flagship instance `mastodon.social`, and another huge instance called `mastodon.online`. They employ people to manage operations & moderation of these servers.

On the Fediverse, there are a lot of people who strongly dislike Gargron. From what I’ve seen, this is mainly caused by Gargron having a very firm idea of what Mastodon should be. He’s not open to other ideas that fall beyond that scope.

In recent months, it seems like Gargron & the non-profit have been very focused on building a Twitter competitor. I’m not sure trying to improve the onboarding process should be a priority until the safety issues have a systemic fix, so I’m not happy about that direction.

## Conclusions

I know I’ve leveled a lot of criticism in the post. I do like the Fediverse — and anything that lets us reclaim the web for ourselves — but it’s kind of difficult to use. Even when you can figure out how to get started, you might still get blasted with nazi DMs.

There’s a lot to fix and improve, but there are also a lot of cool people posting cool stuff.

If there’s anything I’ve gotten wrong here, please let me know. You can find me [@owls@yshi.org](https://mastodon.yshi.org/@owls).
