//
//  March.h
//  MazuMarch
//
//  Created by OA Wu on 2016/4/21.
//  Copyright © 2016年 OA Wu. All rights reserved.
//

#import <Foundation/Foundation.h>

@interface March : NSObject

@property NSString *marchId, *title;

- (March *) initWithDictionary:(NSDictionary *)dictionary;

@end
