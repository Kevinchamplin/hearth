# Your AI shouldn't live in someone else's computer

*Build-in-public post #1 — draft for kevinchamplin.com*

---

Every AI you've ever used lives in a data center you'll never see. You rent it by the
month. Someone logs what you type. And the day the company changes its pricing — or its
mind — your "assistant" changes with it. We've all just quietly accepted that intelligence
is something you lease.

I didn't want to accept that. So this week I built **Hearth** — a beautiful, free AI that
runs *entirely on your own computer*. No account. No subscription. No internet required. And
nothing you say ever leaves your machine.

You can try it right now, in your browser, with zero install: **[hearth.kevinchamplin.com](https://hearth.kevinchamplin.com)**

## The one idea that makes it work

Here's the trick, and it's almost embarrassingly simple: **if the AI runs on the user's
device, it costs me nothing to run — which means it can actually be free, and actually be
private.** Every cloud AI is bleeding money per message; that's why their free tiers are
throttled and their privacy policies are long. Push the computation to the edge — to *your*
laptop's own chip — and both problems disappear at once.

Modern browsers can now run a real language model locally using WebGPU. So Hearth's web
version downloads a small model *once* (about a gigabyte), then runs it right inside your
tab. After that first load it works on a plane, in a cabin, with the wifi off. The model
literally cannot phone home, because there's no home to phone.

## I refused to make it look like a cockpit

The other thing that bothered me about AI tools: they all look the same. Blue gradients,
neon, circuit-board textures, a robot icon. Cold. Technical. Intimidating to anyone who
isn't already a believer.

Hearth goes the opposite way. The whole design language is **warm light, not cold tech** —
a glowing ember that breathes, warm paper, a quiet room you'd actually want to sit in. The
goal was a tool my mom could open and understand in five seconds, that a designer would also
find genuinely beautiful. Local AI deserves a beautiful home, not a dashboard.

## What it actually is

- **Free, forever.** It runs on your hardware, so there's nothing to bill.
- **Private by nature.** The model is on your device; conversations never leave it.
- **Works offline.** One download, then it's yours — internet optional.
- **No account.** You open it and you're talking. That's the whole onboarding.

It's open source, too — the code is on [GitHub](https://github.com/Kevinchamplin/hearth).
A native Mac/Windows/Linux app (with bigger, smarter models) is next, and I'm building the
whole thing in the open.

## Why I'm sharing this

I'm not selling anything here. Hearth is a passion project — the kind of thing you build
because you think it *should* exist. But I also believe the future of AI isn't only giant
models in distant data centers. Some of it belongs right here, on the machine in front of
you, warm and private and yours.

Go light the hearth: **[hearth.kevinchamplin.com](https://hearth.kevinchamplin.com)**. Turn
your wifi off afterward and watch it keep working. That part still makes me smile.

— Kevin
