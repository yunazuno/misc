#!/usr/bin/python
# -*- coding: utf-8 -*-

"""
IPv4/IPv6の優先順位をチェックする．
"""

import socket

def main():
    if (socket.has_ipv6 == False):
        exit("IPv6 not present.")

    ret = socket.getaddrinfo("www.kame.net", 80,
                             socket.AF_UNSPEC, socket.SOCK_STREAM)

    if (ret[0][0] == socket.AF_INET):
        print "First : IPv4\nSecond: IPv6"
    elif (ret[0][0] == socket.AF_INET6):
        print "First : IPv6\nSecond: IPv4"
    else:
        exit("Family not known.")



if __name__ == "__main__":
    main()
