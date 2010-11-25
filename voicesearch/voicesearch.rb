#!/usr/bin/ruby
# -*- coding: utf-8 -*-

require "julius"
require "modrepl"
require "uri"

juli = new Julius()
ffcon = new MozRepl()

juli.connect()
ffcon.connect()

while true
  words = juli.word()
  print words
  cmd = 'content.location.href=http://www.google.com/search?q='
  $KCODE = 'UTF8'
  cmd += URI.encode(words)
  ffcon.command(cmd)
end
