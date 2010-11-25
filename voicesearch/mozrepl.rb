#!/usr/bin/ruby
# -*- coding: utf-8 -*-

#
# Wrapper for MozRepl
#
# based on: http://d.hatena.ne.jp/saitodevel01/20100918/1284817604
#

require 'net/telnet'

class MozRepl
  # Telnet
  @telnet = nil

  def initialize()
    #
  end

  def connect(host = "localhost", port = 4242, prompt = /repl\> \z/n)
    @telnet = Net::Telnet.new("Host" => host, "Port" => port, "Prompt" => prompt)
    @telnet.waitfor(prompt)
  end

  def close()
    @telnet.puts("repl.quit()")
    @telnet.close()
  end

  def command(str)
    @telnet.cmd(str)
  end

end
