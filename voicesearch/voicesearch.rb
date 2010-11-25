#!/usr/bin/ruby
# -*- coding: utf-8 -*-

require "julius"
require "mozrepl"
require "uri"

# Connect julius and MozRepl
juli = Julius.new()
ffcon = MozRepl.new()
juli.connect()
ffcon.connect()

# Wait talking and google it!
words = juli.word()
cmd = 'content.location.href="http://www.google.com/search?q='
cmd += URI.encode(words) + '"'
ffcon.command("repl.home()")
ffcon.command(cmd)
ffcon.close()
