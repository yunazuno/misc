#!/usr/bin/ruby
# -*- coding: utf-8 -*-

#
# Get words from julius
#
# based on: http://d.hatena.ne.jp/kenkitii/20091224/p1
#

require "socket"
require "rubygems"
require "nokogiri"

class Julius

  @sock = nil

  def initialize()
    #
  end

  def connect(host = "localhost", port = 10500)
    @sock = TCPSocket.open(host, port)
  end

  def word()
    source = ""
    while true
      ret = IO::select([@sock])
      ret[0].each do |juli|
        source += juli.recv(65535)
        if source[-2..source.size] == ".\n"
          source.gsub!(/\.\n/, "")
          xml = Nokogiri(source)
          words = (xml/"RECOGOUT"/"SHYPO"/"WHYPO").inject("") {|ws, w| ws + w["WORD"]}
          unless words.empty?
            return words
          end
          source = ""
        end
      end
    end
  end

end
