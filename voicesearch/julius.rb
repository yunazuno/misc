#!/usr/bin/ruby
# -*- coding: utf-8 -*-

require "socket"
require "nokogiri"

class Julius

  @sock = nil

  def initialize()

  end

  def connect(host = "localhost", port = 10500)
    @sock = TCPSocket.open(host, port)
  end

  def word()
    source = ""
    ret = IO::select([@sock])
    ret[0].each do |juli|
      source += juli.resv(65535)
      if source[-2..source.size] == ".\n"
        source.gsub!(/\.\n/, "")
        xml = Nokogiri(source)
        words = (xml/"RECOGOUT"/"SHYPO"/"WHYPO").inject("") {|ws, w| ws + w["WORD"]}
        unless words == ""
          return words
        end
      end
    end
  end

end
